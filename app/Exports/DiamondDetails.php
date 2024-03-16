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
use PDF;

class DiamondDetails implements FromView, WithHeadings,WithEvents,ShouldAutoSize
{
    protected $fromDate;
    protected $toDate;
    protected $agentId;
    protected $partyId;
    protected $remainId;
    protected $colorId;
    protected $shapeId;
    protected $fromweight;
    protected $toweight;

    public function __construct($fromDate, $toDate,$agentId,$remainId,$partyId,$colorId,$shapeId,$fromweight,$toweight)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->agentId = $agentId;
        $this->partyId = $partyId;
        $this->remainId = $remainId;
        $this->colorId = $colorId;
        $this->shapeId = $shapeId;
        $this->fromweight = $fromweight;
        $this->toweight = $toweight;
    }

    public function view(): View
    {
        $diamonddetails = DiamondList::leftJoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
        ->leftjoin('tbl_agent','tbl_agent.id','=','tbl_order_accept.agent_id')
        ->leftjoin('tbl_party','tbl_party.id','=','tbl_order_accept.party_id')

        ->select('tbl_diamond_list.*','tbl_diamond_list.weight as tweight', 'tbl_order_accept.*','tbl_order_accept.weight as aweight','tbl_agent.name as aname','tbl_party.name as pname')

        ->when($this->fromDate && $this->toDate, function ($query) {
            return $query->whereBetween(DB::raw('DATE(tbl_diamond_list.created_at)'), [$this->fromDate, $this->toDate]);
        })
        ->when($this->agentId, function ($query) {
            return $query->where('tbl_diamond_list.agent', $this->agentId);
        })
        ->when($this->partyId, function ($query) {
            return $query->where('tbl_diamond_list.client', $this->partyId);
        })
        ->when($this->remainId, function ($query) {
            return $query->where('tbl_diamond_list.remaining_weight', $this->remainId);
        })
        ->when($this->colorId, function ($query) {
            return $query->where('tbl_diamond_list.color', $this->colorId);
        })
        ->when($this->shapeId, function ($query) {
            return $query->where('tbl_diamond_list.shape', $this->shapeId);
        })
        ->when($this->fromweight && $this->toweight, function ($query) {
            return $query->whereBetween('tbl_diamond_list.weight', [$fromweight, $toweight]);
        })
        ->orderBy('tbl_order_accept.created_at')
        ->get();
        $diamonddetails->transform(function ($entry) {
            $entry->date = date('d-m-Y', strtotime($entry->date));
            return $entry;
        });
        return view('Backend.OrderAccept_wise', [
            'diamonddetails' => $diamonddetails
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
