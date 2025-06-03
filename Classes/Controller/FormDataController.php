<?php
namespace NeosRulez\Neos\Form\SaveToDatabaseFinisher\Controller;

/*
 * This file is part of the NeosRulez.Neos.Form.SaveToDatabaseFinisher package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Model\FormData;
use NeosRulez\Neos\Form\SaveToDatabaseFinisher\Domain\Repository\FormDataRepository;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FormDataController extends ActionController
{

    /**
     * @Flow\Inject
     * @var FormDataRepository
     */
    protected $formDataRepository;

    /**
     * @return void
     */
    public function indexAction(): void
    {
        $formData = $this->formDataRepository->findAll();
        $this->view->assign('formdata', $formData);
    }

    /**
     * @param FormData $data
     * @return void
     */
    public function showAction(FormData $data): void
    {
        $props = json_decode($data->getProps(), true);
        $created = $data->getCreated();
        $form = $data->getForm();
        $result = ['created' => $created, 'props' => $props, 'form' => $form];
        $this->view->assign('data', $result);
    }

    /**
     * @param FormData $data
     * @return void
     */
    public function deleteAction(FormData $data): void
    {
        $this->formDataRepository->remove($data);
        $this->persistenceManager->persistAll();
        $this->redirect('index');
    }

    /**
     * @return void
     */
    public function exportAction(): void
    {
        $items = [];
        $formData = $this->formDataRepository->findAll();
        foreach ($formData as $formDataItem) {
            $items[$formDataItem->getForm()][] = $formDataItem;
        }

        $form = 0;
        $spreadsheet = new Spreadsheet();
        foreach ($items as $itemKey => $item) {
            if ($form === 0) {
                $formDataSheet = $spreadsheet->getActiveSheet();
            } else {
                $formDataSheet = $spreadsheet->createSheet();
            }

            $formDataSheet->setTitle(mb_substr($itemKey, 0, 31));
            $formDataSheet->getHeaderFooter()->setOddHeader('&C&H' . mb_substr($itemKey, 0, 31));

            $header = [];
            $rowIndex = 2;
            foreach ($item as $children) {
                $props = json_decode($children->getProps(), true);

                $colIndex = 1;

                foreach ($props as $prop) {
                    if ($rowIndex === 2) {
                        $header[] = $prop['key'];
                    }

                    if (is_array($prop['value'])) {
                        $newPropValue = '';
                        foreach ($prop['value'] as $propValueKey => $propValue) {
                            $newPropValue .= $propValue . ($propValueKey === (count($prop['value']) -1) ? '' : ', ');
                        }
                        $prop['value'] = $newPropValue;
                    }

                    $column = Coordinate::stringFromColumnIndex($colIndex);
                    $formDataSheet->setCellValue($column . $rowIndex, $prop['value']);
                    $colIndex++;
                }

                $column = Coordinate::stringFromColumnIndex($colIndex);
                $formDataSheet->setCellValue($column . $rowIndex, $children->getCreated());

                $rowIndex++;
            }
            $header = array_values($header);
            $header[] = 'Gesendet';

            $colIndex = 1;
            $startHeader = false;
            foreach ($header as $headerValue) {
                $column = Coordinate::stringFromColumnIndex($colIndex);
                if ($colIndex === 1) {
                    $startHeader = $column;
                }
                $formDataSheet->setCellValue($column . '1', $headerValue);
                $colIndex++;
            }
            $stopHeader = $column;

            if($startHeader && $stopHeader) {
                $formDataSheet->getStyle($startHeader . '1:' . $stopHeader . '1')->getFont()->setBold(true);
            }
            $form++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'export_' . time() . '.xlsx';
        $filePathAndFileName = FLOW_PATH_WEB . '/' . $fileName;
        $writer->save($filePathAndFileName);
        $this->redirectToUri('/' . $fileName);
    }

}
