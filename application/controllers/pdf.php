<?php
class Pdf extends Controller
{
    function Pdf()
    {
        parent::Controller();
        $this->load->library('fpdf');
        $this->load->model('invoices_model');
        $this->load->model('clients_model');
    }
    function invoice($id)
    {
        /*
        |   Get PDF Type - Invoice or Receipt
        */
        $data['pdf_type'] = $this->uri->segment(2);
        /*
        |   Get Invoice Details from Database
        */
        $invoice_id = (int)$id;
        $data['invoice_details'] = $this->invoices_model->get_invoice($this->session->userdata('company'), $this->session->userdata('project_id'), $invoice_id);
        /*
        |   Get Work Item Details from Database
        */
        $work_item_details = $this->invoices_model->get_billables($this->session->userdata('company'), $this->session->userdata('project_id'), $invoice_id);
        /*
        |   Initialise Variables
        */
        $datacount = 0; // Data Counter
        $maxcharcount = 120; // Maximum Characters per line
        $data['totalinvoice'] = 0; // Invoice Total Amount Due
        /*
        |   Loop Through Work Items
        */
        foreach ($work_item_details as $work_item):
            /*
            |   Get Required Work Item Variables
            */
            $description = $work_item['description'];
            $flat_fee = $work_item['flatfee'];
            $hourly_rate = $work_item['hourlyrate'];
            /*
            |   Check Length of Description is Not Greater Than Maximum
            */
            if (strlen($description) < $maxcharcount)
            {
                /*
                |   Within Limits So Display Description on One Line
                */
                $data["invoiceitem"][$datacount][0] = $description;
            }
            else
            {
                /*
                |   Description Exceeds Limits so Split Over Two Lines
                */
                $temp = substr($description, 0, $maxcharcount);
                $last_space = strrpos($temp, " ");
                $data["invoiceitem"][$datacount][0] = substr($description, 0, $last_space);
                $data["invoiceitem"][$datacount][1] = "";
                /*
                |   Line 2
                */
                $datacount++;
                $data["invoiceitem"][$datacount][0] = substr($description, $last_space + 1, $maxcharcount * 2);
            }
            /*
            |   Dispay Flat Fee Amount
            */
            if ($flat_fee > 0)
            {
                $data["invoiceitem"][$datacount][1] = $flat_fee;
            }
            else
            {
                $data["invoiceitem"][$datacount][1] = "";
            }
            $datacount++;
            /*
            |   Get Work Item Actual Times
            */
            $data['work_item_time'][$work_item['unq']] = $this->invoices_model->get_actual_times($this->session->userdata('company'), $this->session->userdata('project_id'), $work_item['unq']);
            /*
            |   Calculate Amount Due - Time Spent x Hourly Rate
            */
            if ($data['work_item_time'][$work_item['unq']] > 0)
            {
                $tracktime = convertTimeForm($data['work_item_time'][$work_item['unq']]);
                /*
                |   If Time is Greater Than 60 Minutes Then Parse Into Hrs and Mins
                */
                if ($data['work_item_time'][$work_item['unq']] < 60)
                {
                    $tracktime = str_replace('0:', '', $tracktime);
                }
                else
                {
                    $tracktime = str_replace(':', ' '.$this->lang->line('invoice_hours').' ', $tracktime);
                }
                $invoiceitem = $tracktime." ".$this->lang->line('invoice_minutes'); 
                $invoiceitem .= " @ ".$hourly_rate." ".$this->lang->line('invoice_per_hour'); 
                $data["invoiceitem"][$datacount][0] = $invoiceitem;
                $invoiceitem = $data['work_item_time'][$work_item['unq']] * ($hourly_rate / 60);
                $data['totalinvoice'] = $data['totalinvoice'] + $invoiceitem;
                $data["invoiceitem"][$datacount][1] = $invoiceitem; // No output for Timetrack Items
                $datacount++;
            }
            $data['totalinvoice'] = $data['totalinvoice'] + $flat_fee;
        endforeach;
        /*
        |   Prepare to Produce PDF Output
        */
        define('FPDF_FONTPATH', $this->config->item('fonts_path'));
        $this->fpdf->Open();
        /*
        |   Table Headers
        */
        $header = array('     '.$this->lang->line('invoice_description'), ' '.$this->lang->line('invoice_amount').' ('.$this->config->item('invoice_currency').')');
        $this->fpdf->AliasNbPages();
        $this->fpdf->SetFont('Arial', '', 12);
        $this->fpdf->AddPage();
        /*
        |   Call Each PDF Section
        */
        $this->header($invoice_id, $data['pdf_type']);
        $this->draw_table($header, $data);
        $this->footer($data['pdf_type']);
        /*
        |   Generate PDF
        */
        $this->fpdf->Output();
    }
    function receipt($id)
    {
        /*
        |   Run Invoice Function
        */
        $this->invoice($id);
    }
    function header($invoiceref, $pdf_type)
    {
        /*
        |   Get Client Details
        */
        $data['client_details'] = $this->clients_model->get_client_from_project($this->session->userdata('company'), $this->session->userdata('project_id'));
        /*
        |   Display Invoice No and Due Date
        */
        $this->fpdf->SetFont('Helvetica', 'B', 10);
        $this->fpdf->SetFillColor(237, 237, 237);
        $this->fpdf->Cell(0, 4, $this->lang->line('invoice_id').': '.str_pad($invoiceref, 7, "0", STR_PAD_LEFT), 0, 0, 'R', true);
        $this->fpdf->SetFont('Helvetica', 'B', 8);
        $this->fpdf->Ln(3);
        $this->fpdf->Cell(0, 10, $this->lang->line('invoice_due_date').': '.date("jS F Y"), 0, 1, 'R');
        /*
        |   Display Company Information
        */
        $this->fpdf->SetFont('Helvetica', 'B', 12);
        $this->fpdf->SetTextColor(255, 153, 0);
        $this->fpdf->Cell(0, 10, $this->config->item('invoice_header'), 0, 0, 'R');
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Ln(5);
        $splitaddress = explode(",", $this->config->item('invoice_address'));
        foreach ($splitaddress as $show_address)
        {
            $this->fpdf->Cell(0, 10, $show_address, 0, 0, 'R');
            $this->fpdf->Ln(5);
        }
        $this->fpdf->SetFont('Arial', '', 8);
        /*
        |   Display Client Name and Address Details
        */
        $this->fpdf->SetY(40);
        $this->fpdf->Cell(10, 10, $data['client_details'][0]['contactname'], 0, 0, 'L');
        $this->fpdf->Ln(5);
        $this->fpdf->Cell(10, 10, $data['client_details'][0]['organisationname'], 0, 0, 'L');
        $this->fpdf->Ln(5);
        $splitaddress = explode("\n", $data['client_details'][0]['address']);
        foreach ($splitaddress as $show_address)
        {
            $this->fpdf->Cell(10, 10, $show_address, 0, 0, 'L');
            $this->fpdf->Ln(5);
        }
        /*
        |   Display Project Name
        */
        $this->fpdf->SetY(80);
        $this->fpdf->Cell(10, 10,  $this->lang->line('project_name').':        '.$this->session->userdata('project_name'), 0, 0, 'L');
        $this->fpdf->SetY(100);
        $this->fpdf->SetFont('Arial', 'B', 10);
        $this->fpdf->Cell(30);
        /*
        |   Display Page Heading
        */
        if ($pdf_type == "receipt") $text = $this->lang->line('invoice_receipt_pdf_header'); 
        else  $text = $this->lang->line('invoice_pdf_header'); 
        $this->fpdf->Cell(130, 10, $text, 0, 0, 'C');
        $this->fpdf->Ln(5);
    }
    function footer($pdf_type)
    {
        $this->fpdf->SetLeftMargin(10);
        if ($pdf_type == "invoice")
        {
            /*  Display Invoice Footer */
            //$this->fpdf->SetY(-120);
            //$this->fpdf->SetFont('Arial', '', 6);
            $this->fpdf->Ln(15);
            $this->fpdf->Cell(0, 10, $this->config->item('invoice_terms'), 0, 0, 'C');
            //$this->fpdf->Ln(15);
            
            $this->fpdf->SetY(-100);
            $this->fpdf->SetFont('Arial', '', 8);
            $this->fpdf->Cell(0, 10, $this->config->item('invoice_footer1'), 0, 0, 'C');
            $this->fpdf->Ln(5);
            $this->fpdf->Cell(0, 10, $this->config->item('invoice_footer2'), 0, 0, 'C');
            $this->fpdf->Ln(5);
            $this->fpdf->Cell(0, 10, $this->config->item('invoice_footer3'), 0, 0, 'C');
            

        } elseif ($pdf_type == "receipt")
        {
            /*
            |   Display Receipt Footer
            */
            $this->fpdf->SetY(-100);
            $this->fpdf->SetFont('Arial', '', 8);
            $this->fpdf->Cell(0, 10, $this->config->item('receipt_footer1'), 0, 0, 'C');
            $this->fpdf->Ln(5);
            $this->fpdf->Cell(0, 10, $this->config->item('receipt_footer2'), 0, 0, 'C');
            $this->fpdf->Ln(5);
            $this->fpdf->Cell(0, 10, $this->config->item('receipt_footer3'), 0, 0, 'C');
            $this->fpdf->Ln(5);
        }
        $this->fpdf->SetLeftMargin(10);
        $this->fpdf->SetY(-50);
        $this->fpdf->SetFont('Arial', 'I', 8);
        /*
        |   Display Generic Footer Text
        */
        $this->fpdf->Cell(0, 10, $this->config->item('invoice_footer_main'), 0, 0, 'C');
    }
    function draw_table($header, $data)
    {
        /*
        |   Display Invoice Main Table
        */
        $this->fpdf->SetY(110);
        $this->fpdf->SetFillColor(128, 128, 255);
        $this->fpdf->SetTextColor(255);
        $this->fpdf->SetDrawColor(128, 128, 128);
        $this->fpdf->SetLineWidth(.2);
        $this->fpdf->SetFont('Arial', '', 8);
        $w = array(165, 30); // Cell Widths
        $header_count = count($header);
        for ($i = 0; $i < $header_count; $i++)
        {
            if ($i != ($header_count - 1)) $this->fpdf->Cell($w[$i], 7, $header[$i], 1, 0, 'L', 1);
            else  $this->fpdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->fpdf->Ln();
        $this->fpdf->SetFillColor(224, 224, 224);
        $this->fpdf->SetTextColor(0);
        $fill = 0;
        /*
        |   Display Invoice Items
        */
        foreach ($data["invoiceitem"] as $row)
        {
            $this->fpdf->Cell($w[0], 6, '     '.$row['0'], 'LR', 0, 'L', $fill);
            if ($row['1'] > 0) $row['1'] = number_format($row['1'], 2);
            $this->fpdf->Cell($w[1], 6, $row['1'].'            ', 'LR', 0, 'R', $fill);
            $this->fpdf->Ln();
            $fill = !$fill;
        }
        $this->fpdf->Cell(array_sum($w), 0, '', 'T');
        $this->fpdf->Ln(5);
        $this->fpdf->SetLeftMargin(125);
        if ($data['pdf_type'] == "invoice")
        {
            /*
            |   Display Invoice Total
            */
            $this->fpdf->Cell(50, 6, '    '.$this->lang->line('invoice_pdf_header').' '.$this->lang->line('invoice_total'), 'LRTB', 0, 'L', 1);
        }
        else
        {
            /*
            |   Display Receipt Total
            */
            $this->fpdf->Cell(50, 6, '    '.$this->lang->line('invoice_receipt_pdf_header').' '.$this->lang->line('invoice_total'), 'LRTB', 0, 'C', 1);
        }
        $this->fpdf->Cell(30, 6, number_format($data['totalinvoice'], 2).'            ', 'LRTB', 0, 'R', 0);
    }
}
/* End of File */
