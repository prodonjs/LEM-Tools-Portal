<?php
App::uses('CakeEventListener', 'Event');
App::uses('CakeEmail', 'Network/Email');
App::uses('CakeLog', 'Log');
App::uses('Order', 'Model');
class OrderEventListener implements CakeEventListener {
    /**
     * Returns all defined EventListener methods
     * @return array
     */
    public function implementedEvents() {
        return array(
            'Model.Order.afterSave' => 'afterSave'
        );
    }

    /**
     * Handles the post-submit processing of Orders including dumping
     * @param array $event
     */
    public function afterSave($event) {
        $order = $event->data['order'];
        /* Interrogate Order status to determine course of action */
        switch($order['Order']['status']) {
            case 'submitted':
                $this->_handleSubmitStatus($order);
                break;
            case 'received':
                $this->_handleReceivedStatus($order);
                break;
            case 'cancelled':
                $this->_handleCancelledStatus($order);
                break;
            case 'completed':
                $this->_handleCompletedStatus($order);
                break;
        }
    } // end afterSubmit()

    /**
     * Handles the post-submit processing of Orders
     * @param array $event
     */
    private function _handleSubmitStatus($order) {
        /* Send e-mail to customer, sales will be bcc'ed */
        $Email = $this->_getMailInstance();
        $orderNo = sprintf('%06d',$order['Order']['id']);
        try {
            $Email->to($order['LemUser']['email'])
                ->bcc(Configure::read('App.ordersEmail'))
                ->from(Configure::read('App.defaultEmail'))
                ->emailFormat(CakeEmail::MESSAGE_HTML)
                ->subject("Web Order #{$orderNo} has been submitted")
                ->template('order_confirmation', 'default')
                ->viewVars(array('order' => $order))
                ->send();
        } catch (SocketException $exc) {
            CakeLog::write('debug', "Order Confirmation E-mail Failed ({$exc->getMessage()})" . json_encode($order));
        }

        /* Update this customer's last transaction date */
        $customer = ClassRegistry::init('Customer');
        $customer->id = $order['Customer']['id'];
        $customer->saveField('last_transaction', date('Y-m-d H:i:s'));

        CakeLog::write('orders', "Order {$order['Order']['id']} Submitted: " . json_encode($order));
    } // end _handleSubmitStatus()

    /**
     * Handles the received status of an order
     * @param array $event
     */
    private function _handleReceivedStatus($order) {
        /* Send e-mail to customer, admin will be bcc'ed */
        $Email = $this->_getMailInstance();
        $orderNo = sprintf('%06d',$order['Order']['id']);
        try {
            $Email->to($order['LemUser']['email'])
                ->bcc(Configure::read('App.ordersEmail'))
                ->from(Configure::read('App.defaultEmail'))
                ->emailFormat(CakeEmail::MESSAGE_HTML)
                ->subject("Order #{$orderNo} has been received and is in processing")
                ->template('order_received', 'default')
                ->viewVars(array('order' => $order))
                ->send();
        } catch (SocketException $exc) {
            CakeLog::write('debug', "Order In-Processing E-mail Failed ({$exc->getMessage()})" . json_encode($order));
        }

        CakeLog::write('orders', "Order {$order['Order']['id']} Received: " . json_encode($order));
    } // end _handleReceivedStatus()

    /**
     * Handles the cancelled status of an order
     * @param array $event
     */
    private function _handleCancelledStatus($order) {
        /* Send e-mail to customer, admin will be bcc'ed */
        $Email = $this->_getMailInstance();
        $orderNo = sprintf('%06d',$order['Order']['id']);
        try {
            $Email->to($order['LemUser']['email'])
                ->bcc(Configure::read('App.ordersEmail'))
                ->from(Configure::read('App.defaultEmail'))
                ->emailFormat(CakeEmail::MESSAGE_HTML)
                ->subject("Web Order #{$orderNo} has been cancelled")
                ->template('order_cancelled', 'default')
                ->viewVars(array('order' => $order))
                ->send();
        } catch (SocketException $exc) {
            CakeLog::write('debug', "Order Cancelled E-mail Failed ({$exc->getMessage()})" . json_encode($order));
        }

        CakeLog::write('orders', "Order {$order['Order']['id']} Cancelled: " . json_encode($order));
    } // end _handleCancelledStatus()

    /**
     * Handles the cancelled status of an order
     * @param array $event
     */
    private function _handleCompletedStatus($order) {
        /* Send e-mail to customer, admin will be bcc'ed */
        $Email = $this->_getMailInstance();
        $orderNo = sprintf('%06d', $order['Order']['id']);
        try {
            $Email->to($order['LemUser']['email'])
                ->bcc(Configure::read('App.ordersEmail'))
                ->from(Configure::read('App.defaultEmail'))
                ->emailFormat(CakeEmail::MESSAGE_HTML)
                ->subject("Order #{$orderNo} is complete and has invoiced")
                ->template('order_completed', 'default')
                ->viewVars(array('order' => $order));
            if(!is_null($order['Order']['invoice'])) {
                $Email->addAttachments(WWW_ROOT . Order::INVOICES_FOLDER . "/{$order['Order']['invoice']}");
            }
            $Email->send();
        } catch (SocketException $exc) {
            CakeLog::write('debug', "Order Completed E-mail Failed ({$exc->getMessage()})" . json_encode($order));
        }

        CakeLog::write('orders', "Order {$order['Order']['id']} Completed: " . json_encode($order));
    } // end _handleCompletedStatus()

    /**
    * Returns a CakeEmail object
    *
    * @return object CakeEmail instance
    * @link http://book.cakephp.org/2.0/en/core-utility-libraries/email.html
    */
	private function _getMailInstance() {
		$emailConfig = Configure::read('Users.emailConfig');
		if ($emailConfig) {
			return new CakeEmail($emailConfig);
		} else {
			return new CakeEmail('default');
		}
	}
} // end class OrderEventListener