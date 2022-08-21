<?php
 
namespace Chilliapple\ThemeSliderFix\Controller\Adminhtml\Slider;
use \Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Rokanthemes\SlideBanner\Controller\Adminhtml\Slider\Save
{


     /**
     * @return void
     */
	public function execute()
    {
        if ($data = $this->getRequest()->getPostValue('slider')) {
            $model = $this->_objectManager->create('Rokanthemes\SlideBanner\Model\Slider');
            
			$storeViewId = $this->getRequest()->getParam("store");
			
			if ($id = $this->getRequest()->getParam('slider_id')) {
				$model->load($id);
			}
			if(isset($data['slider_setting']) && is_array($data['slider_setting']))
                $data['slider_setting'] = json_encode($data['slider_setting']);
                
            
            $data['storeids'] = implode(',',$data['storeids']);

            $model->addData($data);
            

			try {
				$model->save();

				$this->messageManager->addSuccess(__('The Slider has been saved.'));
				$this->_getSession()->setFormData(false);

				if ($this->getRequest()->getParam('back') === 'edit') {
					$this->_redirect(
						'*/*/edit',
						[
							'slider_id' => $model->getId(),
							'_current' => true,
							'current_slider_id' => $this->getRequest()->getParam('current_slider_id'),
							'saveandclose' => $this->getRequest()->getParam('saveandclose'),
						]
					);

					return;
				} elseif ($this->getRequest()->getParam('back') === "new") {
					$this->_redirect('*/*/new', array('_current' => true));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} catch (\Magento\Framework\Model\Exception $e) {
				$this->messageManager->addError($e->getMessage());
			} catch (\RuntimeException $e) {
				$this->messageManager->addError($e->getMessage());
			} catch (\Exception $e) {
				$this->messageManager->addError($e->getMessage());
				$this->messageManager->addException($e, __('Something went wrong while saving the Slider.'));
			}

			$this->_getSession()->setFormData($data);
			$this->_redirect('*/*/edit', array('slider_id' => $this->getRequest()->getParam('slider_id')));
			return;
		}
		$this->_redirect('*/*/');
    }
}