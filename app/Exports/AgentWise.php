<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Party;
use App\Models\DiamondList;
use App\Models\Agent;
use App\Models\OrderAccept;
use DB;

class AgentWise implements FromView, WithHeadings,WithEvents,ShouldAutoSize
{
    protected $fromDate;
    protected $toDate;
    protected $agentId;
    protected $partyId;


    public function __construct($fromDate, $toDate,$agentId,$partyId)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->agentId = $agentId;
        $this->partyId = $partyId;

    }

    public function view(): View
    {
        $agent1 = DB::table('tbl_diamond_list')
        ->select('tbl_diamond_list.*','tbl_diamond_list.weight as tweight', 'tbl_order_accept.*')
        ->leftjoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
        ->leftjoin('tbl_agent','tbl_agent.id','=','tbl_order_accept.agent_id')
        ->where(function ($agent1) {
            $agent1->where('tbl_diamond_list.status', '=','0');
        });
            if ($this->agentId) {
               $agent1->where('tbl_diamond_list.agent', $this->agentId);
            }
            if ($this->partyId) {
                $agent1->where('tbl_diamond_list.client', $this->partyId);
            }
            if($this->fromDate && $this->toDate) {
              $agent1->whereBetween(DB::raw('DATE(tbl_diamond_list.created_at)'),[$this->fromDate, $this->toDate]);
            }
            $agent = $agent1->orderBy('tbl_diamond_list.agent', 'asc')
            ->get();

        return view('Backend.agent_wise', [
            'agent' => $agent
        ]);
    }

    public function headings(): array
    {
        return [
            "SNNO",
            "SELLDATE",
            "SOLDBY",
            "CLIENT",
            "CONTACTNO",
            "SHAPE",
            "WEIGHT",
            "COLOR",
            "CLARITY",
            "CUT",
            "POL",
            "SYMM",
            "FLORO",
            "LAB",
            "LABNO",
            "MM1",
            "MM2",
            "MM3",
            "TABLE",
            "TD",
            "AGENTNAME",
            "AGENTNUMBER",
            "PARTYNAME",
            "PARTYNUMBER",
            "TOTAL",
            "QUANTITY",
            "REMAININGQTY",
            "REASON",
            "AMOUNT",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->freezePane('A2');
            },
        ];
    }

}
