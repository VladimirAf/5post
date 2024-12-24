<?
    namespace Ipol\Fivepost\Api\Adapter;

    interface AdapterInterface
    {
        public function post(string $method, array $dataPost = []);
    }
?>