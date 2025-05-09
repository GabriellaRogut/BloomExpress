<?php
    class Cart{

        public function __construct() {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
        }



        public function addItem($readyMadeID, $price){
            if (isset($_SESSION['cart'][$readyMadeID])) {
                $_SESSION['cart'][$readyMadeID]['quantity'] += 1;
            } else {
                $_SESSION['cart'][$readyMadeID] = [
                    'quantity' => 1,
                    'readyMadeID' => $readyMadeID,
                    'price' => $price
                ];
            }
        }


        public function increaseQty($cartItemID){
            $_SESSION['cart'][$cartItemID]['quantity'] += 1;
        }


        public function decreaseQty($cartItemID){
            if ($_SESSION['cart'][$cartItemID]['quantity'] > 1) {
                $_SESSION['cart'][$cartItemID]['quantity'] -= 1;
            } else {
                $_SESSION['cart'][$cartItemID]['quantity'] = 1;
            }
        }


        public function removeItem($cartItemID){
            unset($_SESSION['cart'][$cartItemID]);
        }


        public function clearCart(){
            $_SESSION['cart'] = [];
        }


        public function getTotal(){
            $total = 0.00;
            foreach ($this->getItems() as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            return $total;
        }


        public static function getTotalSession(){
            $total = 0.00;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            return $total;
        }


        public static function getItems(){
            return $_SESSION['cart'];
        }

    }
?>