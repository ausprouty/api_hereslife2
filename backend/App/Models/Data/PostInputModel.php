<?php
/*
If you’re processing data across multiple methods or 
need to temporarily store it, 
you can use the model as a container for this data. 
The model doesn’t have to save the data to a database; 
it can simply hold it until all processing is complete.
*/

namespace App\Models\Data;

class PostInputModel
{
    private $data;

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    // Other methods for processing the data as needed
}
