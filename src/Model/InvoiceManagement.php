<?php
/**
 * @description Invoice management model
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace D3p1\InstantInvoice\Model;

use Magento\Framework\DB\TransactionFactory;
use Magento\Framework\DB\Transaction;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use D3p1\InstantInvoice\Api\InvoiceManagementInterface;

class InvoiceManagement implements InvoiceManagementInterface
{
    /**
     * @var InvoiceSender
     */
    protected $_invoiceSender;

    /**
     * @var TransactionFactory
     */
    protected $_transactionFactory;

    /**
     * Constructor
     *
     * @param InvoiceSender      $invoiceSender
     * @param TransactionFactory $transactionFactory
     */
    public function __construct(
        InvoiceSender      $invoiceSender,
        TransactionFactory $transactionFactory
    ) {
        $this->_invoiceSender      = $invoiceSender;
        $this->_transactionFactory = $transactionFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function create(Order $order, $sendEmail = true)
    {
        /** @var Transaction $transactionSave */
        $transactionSave = $this->_transactionFactory->create();

        $invoice = $order->prepareInvoice()->register();
        $invoice->getOrder()->setIsInProcess(true);
        $transactionSave->addObject($invoice)->addObject($invoice->getOrder());
        $transactionSave->save();

        if ($sendEmail) {
            $this->_sendInvoiceEmail($invoice);
        }

        return $invoice;
    }

    /**
     * Send invoice email
     *
     * @param  Invoice $invoice
     * @return void
     */
    protected function _sendInvoiceEmail(Invoice $invoice)
    {
        $this->_invoiceSender->send($invoice);
        $invoice->getOrder()->setCustomerNoteNotify(true);
    }
}
