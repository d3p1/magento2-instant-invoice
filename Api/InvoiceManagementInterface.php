<?php
/**
 * @description Invoice management interface
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 * @note        Implementation to provide invoice utils that
 *              automate different invoice processes
 */
namespace Bina\InstantInvoice\Api;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;

interface InvoiceManagementInterface
{
    /**
     * Create invoice from order
     *
     * @param  Order $order
     * @param  bool  $sendEmail
     * @return Invoice
     * @note   It is used the order and invoice model instead
     *         of their interface because it is required
     *         for the implementation logic
     */
    public function create(Order $order, $sendEmail = true);
}
