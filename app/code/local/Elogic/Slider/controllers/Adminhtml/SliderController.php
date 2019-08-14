<?php
/**
 * Slider admin controller
 *
 * @author elogic
 */


/**
 * Class Elogic_Slider_Adminhtml_SliderController
 */
class Elogic_Slider_Adminhtml_SliderController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $contentBlock = $this->getLayout()->createBlock('elogic_slider/adminhtml_slider');
        $this->loadLayout();
        $this->_setActiveMenu('elogic_slider');
        $this->_addContent($contentBlock)->renderLayout();
    }
    /**
     * New slide action
     */

    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit slide action
     */

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $slider = Mage::getModel('elogic_slider/slider')->load($id);
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

        if (!empty($data)) {
            $slider->setData($data);
        }

        Mage::register('elogic_slider_data', $slider);

        $this->loadLayout();
        $this->_setActiveMenu('elogic_slider');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('elogic_slider/adminhtml_slider_edit_container'));

        $this->renderLayout();
    }

    /**
     * Add new slide action
     */

    public function addSlideAction()
    {
        $response = array('success' => true);
        $blockSlide = $this->getLayout()->createBlock('elogic_slider/adminhtml_slider_edit_slide');
        $response['row'] = $blockSlide->getSlideRow();
        $response['html'] = $blockSlide->toHtml();

        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * Upload thumb image action
     */

    public function uploadThumbAction()
    {
        $response = array('success' => true);
        try {
            $postData = $this->getRequest()->getPost();
            $imgFilename = NULL;

            $uploader = new Varien_File_Uploader('image');

            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'png'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);

            // Set media as the upload dir
            $media_path = Mage::getBaseDir('media') . DS . 'elogic' . DS . 'temp' . DS;
            $imgFilename = $media_path . $postData['image'];

            while (file_exists($imgFilename)) {
                $pieces = array();
                $res = preg_match('/^(.+)_(\d+)$/', $imgFilename, $pieces);

                if (!$res) {
                    $imgFilename .= '_1';
                } else {
                    $imgFilename .= '_' . strval(intval($pieces[2]) + 1);
                }
            }

            if (!file_exists($media_path)) {
                mkdir($media_path, 0777);
            }
            // Upload the image
            $res = $uploader->save($media_path, $postData['image']);
            $response['path'] = Mage::getBaseUrl('media') . 'elogic/temp/' . $res['file'];
            return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        }
    }

    /**
     * Save slide action
     */

    public function saveSliderAction()
    {
        $response = array('success' => true);
        $slider = Mage::getModel('elogic_slider/slider');
        $params = $this->getRequest()->getParams();
        $paramSlides = null;

        if (array_key_exists('slides', $params)) {
            $paramSlides = $params['slides'];
            unset($params['slides']);
        }

        if ($id = $this->getRequest()->getPost('id')) {
            $slider->load($id);
        }

        $slider->setData($params);

        try {
            $slider->save();
            $slidesIds = $slider->getSlides()->getAllIds();

            if (!empty($paramSlides)) {
                foreach ($paramSlides as $slide) {
                    $slideData = array();
                    $image = null;

                    foreach ($slide as $slideParam) {
                        if (array_key_exists('blocks', $slideParam)) {
                            $slideParam['params'] = json_encode($slideParam['blocks']);
                            unset($slideParam['blocks']);
                        } else {
                            $slideParam['params'] = '';
                        }

                        if (array_key_exists('image', $slideParam)) {
                            $image = explode('/', $slideParam['image']);
                            $slideParam['image'] = $image[count($image) - 1];
                        }
                        $slideData = array_merge($slideData, $slideParam);
                    }

                    $slideModel = Mage::getModel('elogic_slider/slider_slides');

                    if (array_key_exists('id', $slideData)) {
                        $slideModel->load($slideData['id']);

                        if (!empty($slidesIds)) {
                            foreach ($slidesIds as $key => $val) {
                                if ($val == $slideData['id']) {
                                    unset($slidesIds[$key]);
                                }
                            }
                        }
                        $imagePath = $slideModel->getImagePath(false);
                    }

                    $slideModel->setData($slideData);
                    $slideModel->setSliderId($slider->getId());
                    $slideModel->save();

                    if (array_key_exists('image', $slideData) && preg_match('/temp/', implode('/', $image))) {
                        $mediaTempImg = Mage::getBaseDir('media') . DS . 'elogic' . DS . 'temp' . DS . $slideData['image'];

                        if (array_key_exists('id', $slideData)) {
                            if (isset($imagePath) && file_exists($imagePath)) {
                                unlink($imagePath);
                            }
                        }

                        if (file_exists($mediaTempImg)) {
                            $sliderPath = Mage::getBaseDir('media') . DS . 'elogic' . DS . 'slider' . DS . $slider->getId() . DS;
                            $filePath = $sliderPath . $slideModel->getId() . DS;

                            if (!file_exists(Mage::getBaseDir('media') . DS . 'elogic' . DS . 'slider' . DS)) {
                                mkdir(Mage::getBaseDir('media') . DS . 'elogic' . DS . 'slider' . DS, 0777);

                            }

                            if (!file_exists($sliderPath)) {
                                mkdir($sliderPath, 0777);
                            }

                            if (!file_exists($filePath)) {
                                mkdir($filePath, 0777);
                            }

                            copy($mediaTempImg, $filePath . $slideData['image']);
                            unlink($mediaTempImg);
                        }
                    }
                }

                if (!empty($slidesIds)) {
                    foreach ($slidesIds as $oldSlide) {
                        $slideModel = Mage::getModel('elogic_slider/slider_slides')->load($oldSlide);
                        $slideModel->delete();
                    }
                }
            }

            $response['message'] = Mage::helper('elogic_slider')->__('Slider has been saved successfully');
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            $response['redirect'] = $this->getUrl('*/*/');
            return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        }
    }

    /**
     * Delete slide action
     */

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id', false)) {
            try {
                $slider = Mage::getModel('elogic_slider/slider')->load($id);
                $slider->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('elogic_slider')->__('Slider has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/');
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('elogic_slider')->__('Unable to find the slider to delete.'));
        $this->_redirect('*/*/');
    }

    protected function _isAllowed()
    {
        return true;
    }


}
