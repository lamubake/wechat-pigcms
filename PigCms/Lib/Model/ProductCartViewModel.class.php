<?php
/**
 * 订单视图
 * User: pigcms_21
 * Date: 2014/12/29
 * Time: 13:11
 */

class ProductCartViewModel extends ViewModel
{
    public $viewFields = array(
        'ProductCart' => array('*'),
        'DistributorOrder' => array('did','_on'=>'ProductCart.id = DistributorOrder.order_id'),
    );
}