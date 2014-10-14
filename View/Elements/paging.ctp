<?php
echo $this->Paginator->counter(array(
    'format' => 'Page {:page} of {:pages}, showing {:current} {:model} out of {:count} total, starting on record {:start}, ending on {:end}'
)); 
?>