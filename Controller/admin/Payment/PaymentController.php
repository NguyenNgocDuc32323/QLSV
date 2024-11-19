<?php
require_once '../../Model/admin/Payment.php';
require_once '../../config/database.php';
class PaymentController{
   private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getAllPayment(){
        $paymentModel = new Paymnent($this->conn);
        $payments = $paymentModel->getAllPayment();
        return $payments;
    }
    public function searchPayment($string){
        $paymentModel = new Paymnent($this->conn);
        $payments = $paymentModel->searchPayment($string);
        return $payments;
    }
    public function getStudent() {
        $paymentModel = new Paymnent($this->conn);
        $students = $paymentModel->getStudent();
        return $students;
    }
    public function createPayment($ten_phong, $so_tien, $id_hoc_sinh, $method, $ngay_thanh_toan, $trang_thai){
        $paymentModel = new Paymnent($this->conn);
        $checkCreatePayment = $paymentModel->createPayment($ten_phong, $so_tien, $id_hoc_sinh, $method, $ngay_thanh_toan, $trang_thai);
        return $checkCreatePayment;
    }
    public function getRoom(){
        $paymentModel = new Paymnent($this->conn);
        $rooms = $paymentModel->getRoom();
        return $rooms;
    }
    public function deletePayment($payment_id){
        $paymentModel = new Paymnent($this->conn);
        $checkDeletePayment = $paymentModel->deletePayment($payment_id);
        return $checkDeletePayment;
    }
    public function getPaymentById($id){
        $paymentModel = new Paymnent($this->conn);
        $payment = $paymentModel->getPaymentById($id);
        return $payment;
    }
    public function editPayment($payment_id, $ten_phong, $so_tien, $id_hoc_sinh, $method, $ngay_thanh_toan, $trang_thai){
        $paymentModel = new Paymnent($this->conn);
        $checkEditPayment = $paymentModel->editPayment($payment_id, $ten_phong, $so_tien, $id_hoc_sinh, $method, $ngay_thanh_toan, $trang_thai);
        return $checkEditPayment;
    }
}
?>