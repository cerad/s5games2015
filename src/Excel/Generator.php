<?php

/* ========================================================================
 * Base class for exporting excel spreadsheets
 * 
 * Assume for now that Excell2007 is being written
 * 
 * Not all of the formatting stuff works properly under Excel5 aka 2003
 */
namespace Cerad\Component\Excel;

class Generator
{
    protected function createSpreadSheet()
    {
        return new \PHPExcel();
    }
    protected function createWriter($ss)
    {
        return \PHPExcel_IOFactory::createWriter($ss, 'Excel2007');
    }
  protected function setRowValues($ws,$row,$values)
  {
    $col = 0;
    foreach($values as $value) {
      $ws->setCellValueByColumnAndRow($col++,$row,$value);
    }
  }
    /* ======================================================================
     * Set a cell value with an optional format
     *
    protected function setCellValueByColumnAndRowx($ws,$col,$row,$value,$format = null)
    {
        $ws->setCellValueByColumnAndRow($col,$row,$value);
        if (!$format) return;

        $coord = \PHPExcel_Cell::stringFromColumnIndex($col) . $row;
        
        $ws->getStyle($coord)->getNumberFormat()->setFormatCode($format);
    }*/
  /* ========================================================
   * Returns the excel numeric value for a given time
   */
  protected function getNumericTime($dt)
  {
    $hours   = $dt->format('H');
    $minutes = $dt->format('i');
        
    return ($hours / 24) + ($minutes / 1440);
  }
  protected function getNumericDate($dt)
  {
    $date = $dt->format('Y-m-d');
        
    return \PHPExcel_Shared_Date::stringToExcel($date);
  }
    
    /* ===============================================================
     * Make formatting across multiple sheets a bit easier
     * Might combine this later into single array
     *
    protected $columnWidths  = array();
    protected $columnCenters = array();
    protected $columnFormats = array();  // Maybe for dates and times?
    
    protected function setHeadersx($ws,$headers,$row = 1)
    {
        $col = 0;
        foreach($headers as $header)
        {
            if (isset($this->columnWidths[$header]))
            {
                $ws->getColumnDimensionByColumn($col)->setWidth($this->columnWidths[$header]);
            }
            $ws->setCellValueByColumnAndRow($col,$row,$header);

            if (in_array($header,$this->columnCenters) == true)
            {
                $coord = \PHPExcel_Cell::stringFromColumnIndex($col) . $row;
                
              //$ws->getStyle($coord)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $ws->getStyle($col)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
            $col++;
        }
        return $row;
    }*/
  /* ==================================================
   * This should be merged with set headers
   * Might be a centering issus
   */  
  protected function writeHeaders($ws,$row,$headers,$widths = [])
  {
    $col = 0;
    foreach($headers as $header)
    {
      $width = isset($widths[$header]) ? $widths[$header] : 16;
            
      $ws->getColumnDimensionByColumn($col)->setWidth($width);
      $ws->setCellValueByColumnAndRow($col,$row,$header);
      $col++;
    }
  }
 
  /* =======================================================
   * Called by controller to get the content
   */
  protected $ss;

  public function getBuffer($ss = null)
  {
    return $this->getContents($ss);
  }
  public function getContents($ss = null)
  {
    if (!$ss) $ss = $this->ss;
    if (!$ss) return null;

    $objWriter = $this->createWriter($ss);

    ob_start();
    $objWriter->save('php://output');
    return ob_get_clean();
  }
}
