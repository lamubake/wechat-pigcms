<?php
/**
 * 订单商品视图
 * User: pigcms_21
 * Date: 2014/12/29
 * Time: 13:11
 */

class ProductCartListViewModel extends ViewModel
{
    public $viewFields = array(
        'ProductCartList' => array('*'),
        'Product' => array('name', 'logourl', '_on' => 'Product.id = ProductCartList.productid'),
        'ProductCat' => array('norms', 'color', '_on' => 'Product.catid = ProductCat.id'),
    );
}