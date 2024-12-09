<?php
namespace App\Contracts;

use App\Exceptions\PropertyNotExistsException;
use Illuminate\Http\Request;

abstract class EntitySchema
{
    /**
     * Create the schema for an entity from request.
     * 
     * @param Request $request
     * @return EntitySchema
     */
    abstract static public function fromRequest(Request $request): self;

    /**
     * Convert the schema to array.
     * 
     * @return array
     */
    abstract public function toArray(): array;

    public function __get(string $property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        $entityName = get_called_class();

        throw new PropertyNotExistsException("Property {$property} does not exists on the instance of `$entityName`.");
    }
}
