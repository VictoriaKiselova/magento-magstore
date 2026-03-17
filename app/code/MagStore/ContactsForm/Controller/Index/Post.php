<?php

declare(strict_types=1);

namespace MagStore\ContactsForm\Controller\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\DataObject;

class Post implements HttpPostActionInterface
{
    protected $request;
    protected $resultFactory;
    protected $transportBuilder;
    protected $storeManager;
    protected $customerSession;
    protected $messageManager;

    public function __construct(
        HttpRequest $request,
        ResultFactory $resultFactory,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        CustomerSession $customerSession,
        ManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $postData = $this->request->getPostValue();

        if (!$postData) {
            return $resultRedirect->setPath('/');
        }

        try {
            $customerId = $this->customerSession->isLoggedIn()
                ? (string)$this->customerSession->getCustomerId()
                : 'Не авторизовано';

            $templateData = new DataObject();
            $templateData->setData($postData);
            $templateData->setData('customer_status', $customerId);

            $date = new \DateTime();
            $formattedDate = $date->format('l, d F Y H:i');

            $transport = $this->transportBuilder
                ->setTemplateIdentifier('contact_email')
                ->setTemplateOptions([
                    'area' => 'frontend',
                    'store' => $this->storeManager->getStore()->getId()
                ])
                ->setTemplateVars([
                    'data' => $templateData,
                    'date' => $formattedDate
                ])
                ->setFrom(['email' => 'noreply@yourstore.test', 'name' => 'Store'])
                ->addTo('admin@yourstore.test')
                ->getTransport();

            $transport->sendMessage();
            $this->messageManager->addSuccessMessage(__('Ваш запит успішно відправлено.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setRefererUrl();
    }
}
