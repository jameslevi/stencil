<?php

namespace Graphite\Component\Stencil;

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
    private $var_type = 'mixed';

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
        return str_equals($this->getType(), "var");
    }

    /**
     * Return true if comment is for method.
     * 
     * @return  bool
     */
    public function forMethod()
    {
        return str_equals($this->getType(), "method");
    }

    /**
     * Set variable data type.
     * 
     * @param   string $type
     * @return  $this
     */
    public function setVarType(string $type)
    {
        $this->var_type = $type;

        return $this;
    }

    /**
     * Return the variable data type.
     * 
     * @return  string
     */
    public function getVarType()
    {
        return $this->var_type;
    }

    /**
     * Set comment description content.
     * 
     * @param   string $description
     * @return  $this
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
     * @param   string $data_type
     * @return  $this
     */
    public function addParam(string $name, string $data_type, string $description = null)
    {
        $this->params[str_to_snake($name)] = array(
            'type'           => $data_type,
            'description'    => trim($description),
        );

        return $this;
    }

    /**
     * Add new mixed parameter.
     * 
     * @param   string $name
     * @param   string $description
     * @return  $this
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
     * @return  $this
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
     * @return  $this
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
     * @return  $this
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
     * @return  $this
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
     * @return  $this
     */
    public function addFloatParam(string $name, string $description = null)
    {
        return $this->addParam($name, 'float', $description);
    }

    /**
     * Set the data type of return value.
     * 
     * @param   string $type
     * @return  $this
     */
    public function setReturnType(string $type)
    {
        $this->return = $type;

        return $this;
    }

    /**
     * Set return data type as void.
     * 
     * @return  $this
     */
    public function returnVoid()
    {
        return $this->setReturnType('void');
    }

    /**
     * Set return data type as mixed.
     * 
     * @return  $this
     */
    public function returnMixed()
    {
        return $this->setReturnType('mixed');
    }

    /**
     * Set return data type as string.
     * 
     * @return  $this
     */
    public function returnString()
    {
        return $this->setReturnType('string');
    }

    /**
     * Set return data type as integer.
     * 
     * @return  $this
     */
    public function returnInt()
    {
        return $this->setReturnType('int');
    }

    /**
     * Set return data type as boolean.
     * 
     * @return  $this
     */
    public function returnBool()
    {
        return $this->setReturnType('bool');
    }

    /**
     * Set return data type as array.
     * 
     * @return  $this
     */
    public function returnArray()
    {
        return $this->setReturnType('array');
    }

    /**
     * Set return data type as float.
     * 
     * @return  $this
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
        $segments[] = " * " . $this->description;
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
            $segments[] = " * @var " . $this->var_type;
        }

        $segments[] = " */";

        return $segments;
    }

    /**
     * Create a comment for variables.
     * 
     * @param   string $type
     * @param   string $description
     * @return  $this
     */
    public static function makeVar(string $type, string $description)
    {
        return (new self('var'))->setVarType($type)->setDescription($description);
    }

    /**
     * Create a comment for variable with mixed values.
     * 
     * @param   string $description
     * @return  $this
     */
    public static function makeMixedVar(string $description)
    {
        return self::makeVar('mixed', $description);
    }

    /**
     * Create a comment for string variable.
     * 
     * @param   string $description
     * @return  $this
     */
    public static function makeStringVar(string $description)
    {
        return self::makeVar('string', $description);
    }

    /**
     * Create a comment for integer variable.
     * 
     * @param   string $description
     * @return  $this
     */
    public static function makeIntVar(string $description)
    {
        return self::makeVar('int', $description);
    }

    /**
     * Create a comment for boolean variable.
     * 
     * @param   string $description
     * @return  $this
     */
    public static function makeBoolVar(string $description)
    {
        return self::makeVar('bool', $description);
    }

    /**
     * Create a comment for array variable.
     * 
     * @param   string $description
     * @return  $this
     */
    public static function makeArrayVar(string $description)
    {
        return self::makeVar('array', $description);
    }

    /**
     * Create a comment for float variable.
     * 
     * @param   string $description
     * @return  $this
     */
    public static function makeFloatval(string $description)
    {
        return self::makeVar('float', $description);
    }

    /**
     * Create a new method comment.
     * 
     * @param   string $description
     * @return  $this
     */
    public static function makeMethod(string $description)
    {
        return (new self('method'))->setDescription($description);
    }

}