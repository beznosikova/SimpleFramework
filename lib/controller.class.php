<?php
class Controller
{
    protected $data;
    protected $model;
    protected $params;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params): void
    {
        $this->params = $params;
    }

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->params = App::getRouter()->getParams();
    }
}