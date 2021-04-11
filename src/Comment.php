<?php

namespace Stencil;

class Comment
{
    /**
     * Type of comment to generate.
     * 
     * @var string
     */

    private $type;

    /**
     * Set variable data type.
     * 
     * @var string
     */

    private $vartype = 'mixed';

    /**
     * Comment description content.
     * 
     * @var string
     */

    private $description;

    /**
     * Store parameter documentations.
     * 
     * @var array
     */

    private $params = array();

    /**
     * Datatype of return value.
     * 
     * @var string
     */

    private $return = 'void';

    /**
     * Construct a new instance.
     * 
     * @param   string $type
     * @return  void
     */

    public function __construct(string $type) {
        $this->type = $type;
    }

    /**
     * Return the type of comment.
     * 
     * @return  string
     */

    public function getType()
    {
        return strtolower($this->type);
    }

    /**
     * Return true if comment is for variable.
     * 
     * @return bool
     */

    public function forVariable()
    {
        return $this->getType() === "var";
    }

    /**
     * Return true if comment is for method.
     * 
     * @return  bool
     */

    public function forMethod()
    {
        return $this->getType() === "method";
    }

    /**
     * Set variable data type.
     * 
     * @param   string $type
     * @return  \Stencil\Comment
     */

    public function setVarType(string $type)
    {
        $this->vartype = $type;

        return $this;
    }

    /**
     * Set comment description content.
     * 
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public function setDescription(string $description)
    {
        $this->description = trim($description);

        return $this;
    }

    /**
     * Return comment description content.
     * 
     * @return  string
     */

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add new parameter documentation.
     * 
     * @param   string $name
     * @param   string $datatype
     * @return  \Stencil\Comment
     */

    public function addParam(string $name, string $datatype, string $description = null)
    {
        $this->params[$name] = array(
            'type'           => $datatype,
            'description'    => $description,
        );

        return $this;
    }

    /**
     * Add new mixed parameter.
     * 
     * @param   string $name
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public function addMixedParam(string $name, string $description)
    {
        return $this->addParam($name, 'mixed', $description);
    }

    /**
     * Add new string parameter.
     * 
     * @param   string $name
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public function addStringParam(string $name, string $description = null)
    {
        return $this->addParam($name, 'string', $description);
    }

    /**
     * Add new integer parameter.
     * 
     * @param   string $name
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public function addIntegerParam(string $name, string $description = null)
    {
        return $this->addParam($name, 'int', $description);
    }

    /**
     * Add new boolean parameter.
     * 
     * @param   string $name
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public function addBoolParam(string $name, string $description = null)
    {
        return $this->addParam($name, 'bool', $description);
    }

    /**
     * Add new array parameter.
     * 
     * @param   string $name
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public function addArrayParam(string $name, string $description = null)
    {
        return $this->addParam($name, 'array', $description);
    }

    /**
     * Add new float parameter.
     * 
     * @param   string $name
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public function addFloatParam(string $name, string $description = null)
    {
        return $this->addParam($name, 'float', $description);
    }

    /**
     * Set the data type of return value.
     * 
     * @param   string $type
     * @return  \Stencil\Comment
     */

    public function setReturnType(string $type)
    {
        $this->return = $type;

        return $this;
    }

    /**
     * Set return data type as void.
     * 
     * @return  \Stencil\Comment
     */

    public function returnVoid()
    {
        return $this->setReturnType('void');
    }

    /**
     * Set return data type as mixed.
     * 
     * @return  \Stencil\Comment
     */

    public function returnMixed()
    {
        return $this->setReturnType('mixed');
    }

    /**
     * Set return data type as string.
     * 
     * @return  \Stencil\Comment
     */

    public function returnString()
    {
        return $this->setReturnType('string');
    }

    /**
     * Set return data type as integer.
     * 
     * @return  \Stencil\Comment
     */

    public function returnInt()
    {
        return $this->setReturnType('int');
    }

    /**
     * Set return data type as boolean.
     * 
     * @return  \Stencil\Comment
     */

    public function returnBool()
    {
        return $this->setReturnType('bool');
    }

    /**
     * Set return data type as array.
     * 
     * @return  \Stencil\Comment
     */

    public function returnArray()
    {
        return $this->setReturnType('array');
    }

    /**
     * Set return data type as float.
     * 
     * @return  \Stencil\Comment
     */

    public function returnFloat()
    {
        return $this->setReturnType('float');
    }

    /**
     * Return each comment line template.
     * 
     * @return  array
     */

    public function templates()
    {
        $segments = array();

        $segments[] = "/**";
        $segments[] = " * " . trim($this->description);
        $segments[] = " * ";

        if($this->forMethod())
        {
            foreach($this->params as $key => $param)
            {
                $segments[] = " * @param  " . $param['type'] . " $" . $key . " " . $param['description'];
            }

            $segments[] = " * @return " . $this->return;
        }
        else
        {
            $segments[] = " * @var " . $this->vartype;
        }

        $segments[] = " */";

        return $segments;
    }

    /**
     * Create a comment for variables.
     * 
     * @param   string $type
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public static function var(string $type, string $description)
    {
        return (new self('var'))->setVarType($type)->setDescription($description);
    }

    /**
     * Create a comment for variable with mixed values.
     * 
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public static function mixedVar(string $description)
    {
        return self::var('mixed', $description);
    }

    /**
     * Create a comment for string variable.
     * 
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public static function stringVar(string $description)
    {
        return self::var('string', $description);
    }

    /**
     * Create a comment for integer variable.
     * 
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public static function intVar(string $description)
    {
        return self::var('int', $description);
    }

    /**
     * Create a comment for boolean variable.
     * 
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public static function boolVar(string $description)
    {
        return self::var('bool', $description);
    }

    /**
     * Create a comment for array variable.
     * 
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public static function arrayVar(string $description)
    {
        return self::var('array', $description);
    }

    /**
     * Create a comment for float variable.
     * 
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public static function floatval(string $description)
    {
        return self::var('float', $description);
    }

    /**
     * Create a new method comment.
     * 
     * @param   string $description
     * @return  \Stencil\Comment
     */

    public static function method(string $description)
    {
        return (new self('method'))->setDescription($description);
    }

}