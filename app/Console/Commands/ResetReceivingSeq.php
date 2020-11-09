<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReceivingSeqModel;


class ResetReceivingSeq extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receiving_seq:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Receiving Sequence';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        $receivingSeqModel = new ReceivingSeqModel;
        $receivingSeqModel->createDelete();
        
    }
}
