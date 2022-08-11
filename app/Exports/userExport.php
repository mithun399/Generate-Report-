<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;




class userExport implements 
FromCollection,ShouldAutoSize, 
WithHeadings,WithMapping,WithEvents,
WithPreCalculateFormulas
{
    /**
    * @return \Illuminate\Support\Collection
    */
   
    public function collection()
    {
        $purchase=Purchase::orderByRaw("CAST(purchase_quantity as UNSIGNED) DESC")->limit(2)->get();
        return $purchase;
       
    }
   
    
    function headings(): array
    {
        return [
            'Product Name',
            'Customer Name',
            'Quantity',
            'Price',
            'Total'
            
        ];
    }
   
    function map($purchase):array{
        return [
            $purchase->product_name,
            $purchase->name,
            $purchase->purchase_quantity,
            $purchase->product_price,
            $purchase->purchase_quantity*$purchase->product_price,
            
        ]; 
           
       
        
    }
   
    function registerEvents(): array
    {
        return [
            AfterSheet::class=>function(AfterSheet $event){
                $event->sheet->getStyle('A1:E1')->applyFromArray([
                    'font'=>['bold'=>true]
                ]);
                $event->sheet->mergeCells('A5:B5',function($cell){  
                    
                    $cell->setCellValue('Gross Total'); 

                });
                
                $sheet = $event->sheet->getDelegate();

                $sheet->setCellValue('A4', '...');
                $sheet->setCellValue('B4', '...');
                $sheet->setCellValue('C4', '...');
                $sheet->setCellValue('D4', '...');
                $sheet->setCellValue('E4', '...');

                 $sheet->setCellValue('A5', 'Gross Total:')->getStyle('A5')
                 ->getAlignment()
                 ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                  $sheet->setCellValue('C5','=SUM(C2:C3)');
                  $sheet->setCellValue('D5','=SUM(D2:D3)');
                  $sheet->setCellValue('E5','=SUM(E2:E3)');

                

            },
           
        
        ];
    
    }
   
   


}