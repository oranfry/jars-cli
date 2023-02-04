<?php

namespace jars\cli;

use jars\events\entryimported;
use jars\events\importline;
use jars\events\takeanumber;

class ImportListener implements entryimported, importline, takeanumber
{
    var $feedback_handler;

    function __construct($feedback_handler)
    {
        $this->feedback_handler = $feedback_handler;
    }

    public function handle_takeanumber(int $pointer, string $id)
    {
        fwrite($this->feedback_handler, 'issued: ' . $pointer . ' ' . $id . "\n");
    }

    public function handle_entryimported()
    {
        fwrite($this->feedback_handler, 'entry imported' . "\n");
        fflush($this->feedback_handler);
    }

    public function handle_importline(string $table)
    {
        fwrite($this->feedback_handler, 'importline ' . $table . "\n");
    }
}
