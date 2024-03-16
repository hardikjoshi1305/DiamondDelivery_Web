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

    class generateExcel implements FromView, WithHeadings, WithEvents, ShouldAutoSize
    {
        protected $accessToken;
        protected $labno;

        public function __construct($accessToken, $labno)
        {
            $this->accessToken = $accessToken;
            $this->labno = $labno;
        }

    public function view(): View
    {
        $result = DiamondList::leftJoin('tbl_agent', 'tbl_agent.name', '=', 'tbl_diamond_list.agent')
        ->leftjoin('tbl_party', 'tbl_party.name', '=', 'tbl_diamond_list.client')
        ->whereIn('lab_no', $this->labno)
        ->select('tbl_diamond_list.*', 'tbl_party.id as party_id', 'tbl_agent.id as agent_id')
        ->where('agent', '=', $this->accessToken->name)
        ->get();
        return view('Backend.GenerateExcel', [
            'data' => $result,
        ]);
    }

    public function headings(): array
    {
        // Customize these headings based on your data structure
        return [
            'ID',
            'Shipment Date',
            'Shipment Mode',
            'SN No',
            'Sell Date',
            'Sold By',
            'Client',
            'Contact No',
            'Shape',
            'Weight',
            'PCS',
            'Color',
            'Clarity',
            'Cut',
            'Pol',
            'Symm',
            'Floro',
            'Lab',
            'Lab No',
            'MM1',
            'MM2',
            'MM3',
            'Table',
            'TD',
            'Rate',
            'Total',
            'Agent',
            'Remaining Weight',
            'Status',
            'Created At',
            'Updated At',
            'Party ID',
            'Agent ID',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->freezePane('A2');
                $event->sheet->autoSize();
            },
        ];
    }
}

