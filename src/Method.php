<?php

namespace Graphite\Component\Stencil;

class Method
{
    /**
     * Class function name.
     * 
     * @var string
     */
    private $name;

    /**
     * Define if method is static or not.
     * 
     * @var bool
     */
    private $static = false;

    /**
     * Store method visibility.
     * 
     * @var string
     */
    private $visibility = 'public';

    /**
     * Store method arguments.
     * 
     * @var array
     */
    private $arguments = array();

    /**
     * Lines inside method function.
     * 
     * @var array
     */
    private $body = array();

    /**
     * If method is an abstract method.
     * 
     * @var bool
     */
    private $abstract = false;

    /**
     * Set number of spaces in the beginning of line.
     * 
     * @var int
     */
    private $indention = 0;

    /**
     * Construct a new method object.
     * 
     * @param   string $name
     * @return  void
     */
    public function __construct(string $name, bool $static = false)
    {
        $this->name         = $name;
        $this->static       = $static;
    }

    /**
     * Set number of spaces in the beginning of line.
     * 
     * @param   int $indention
     * @return  $this
     */
    public function setIndention(int $indention)
    {
        $this->indention = $indention * 4;

        return $this;
    }

    /**
     * Return method name.
     * 
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set method as static function.
     * 
     * @return $this
     */
    public function setAsStatic()
    {
        $this->static = true;

        return $this;
    }

    /**
     * Set method as an abstract method.
     * 
     * @return  $this
     */
    public function setAsAbstract()
    {
        $this->abstract = true;

        return $this;
    }

    /**
     * Set method visibility.
     * 
     * @param   string $visibility
     * @return  $this
     */
    public function setVisibility(string $visibility)
    {
        $visibility = strtolower($visibility);

        if(in_array($visibility, ['public', 'private', 'protected']))
        {
            $this->visibility = $visibility;
        }

        return $this;
    }

    /**
     * Return method visibility.
     * 
     * @return  string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set method visibility to public.
     * 
     * @return  $this
     */
    public function setPublic()
    {
        return $this->setVisibility('public');
    }

    /**
     * Set method visibility to private.
     * 
     * @return  $this
     */
    public function setPrivate()
    {
        return $this->setVisibility('private');
    }

    /**
     * Set method visibility to protected.
     * 
     * @return  $this
     */
    public function setProtected()
    {
        return $this->setVisibility('protected');
    }

    /**
     * Return true if method is a static method.
     * 
     * @return  bool
     */
    public function isStatic()
    {
        return $this->static;
    }

    /**
     * Add new method arguments.
     * 
     * @param   string $name
     * @param   mixed $default
     * @param   string $datatype
     * @return  $this
     */
    public function addParam(string $name, $default = null, string $datatype = null)
    {
        $this->arguments[str_to_snake($name)] = array('value' => $default, 'datatype' => $datatype);

        return $this;
    }

    /**
     * Add new argument that requires string.
     * 
     * @param   string $name
     * @param   string $default
     * @return  $this
     */
    public function addStringParam(string $name, string $default = null)
    {
        return $this->addParam($name, $default, 'string');
    }

    /**
     * Add new argument that requires integer.
     * 
     * @param   string $name
     * @param   int $default
     * @return  $this
     */
    public function addIntegerParam(string $name, int $default = null)
    {
        return $this->addParam($name, $default, 'int');
    }

    /**
     * Add new argument that requires boolean.
     * 
     * @param   string $name
     * @param   bool $default
     * @return  $this
     */
    public function addBoolParam(string $name, bool $default = null)
    {
        return $this->addParam($name, $default, 'bool');
    }

    /**
     * Add new argument that requires array.
     * 
     * @param   string $name
     * @param   array $default
     * @return  $this
     */
    public function addArrayParam(string $name, array $default = null)
    {
        return $this->addParam($name, $default, 'array');
    }

    /**
     * Add new argument that requires float.
     * 
     * @param   string $name
     * @param   float $default
     * @return  $this
     */
    public function addFloatParam(string $name, float $default = null)
    {
        return $this->addParam($name, $default, 'float');
    }

    /**
     * Add new line inside the method.
     * 
     * @param   string $string
     * @param   int $indention
     * @return  $this
     */
    public function raw(string $string, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        $this->body[] = str_repeat(' ', $indention) . $string;

        return $this;
    }

    /**
     * Return generated method template.
     * 
     * @return  array
     */
    public function templates()
    {
        $templates = array();
        $abstract = $this->abstract;
        $template = $this->visibility . " "; 
        
        if($abstract)
        {
            $template .= "abstract ";
        }

        $template .= ($this->static ? "static " : "") . "function " . str_to_camel($this->name) . "(";

        // Append the method arguments.
        if(!empty($this->arguments))
        {
            foreach($this->arguments as $key => $arg)
            {
                if(!is_null($arg['datatype']))
                {
                    $template .= $arg['datatype'] . " ";
                }

                $template .= "$" . $key;

                if(!is_null($arg['value']))
                {
                    $value = $arg['value'];

                    if(is_bool($value))
                    {
                        $value = $value ? "true" : "false";
                    }
                    else if(is_string($value))
                    {
                        $value = '"' . addslashes($value) . '"';
                    }
                    else if(is_array($value))
                    {
                        $value = json_encode($value);
                    }

                    $template .= " = " . $value;
                }

                $template .= ", ";
            }

            $template = substr($template, 0, strlen($template) - 2);
        }

        $template .= ")";

        if($abstract)
        {
            $template .= ";";
        }

        $templates[] = $template;

        // Append method body if set as non-abstract method.
        if(!$abstract)
        {
            $templates[] = "{";

            if(!is_null($this->body))
            {
                foreach($this->body as $body)
                {
                    $templates[] = $body;
                }
            }

            $templates[] = "}";
        }

        return $templates;
    }

    /**
     * Instantiate a new public method.
     * 
     * @param   string $name
     * @return  $this
     */
    public static function makePublic(string $name)
    {
        return (new self($name, false))->setPublic();
    }

    /**
     * Instantiate a new public static method.
     * 
     * @param   string $name
     * @return  $this
     */
    public static function makePublicStatic(string $name)
    {
        return self::makePublic($name)->setAsStatic();
    }

    /**
     * Instantiate a new private method.
     * 
     * @param   string $name
     * @return  $this
     */
    public static function makePrivate(string $name)
    {
        return (new self($name, false))->setPrivate();
    }

    /**
     * Instantiate a new private static method.
     * 
     * @param   string $name
     * @return  $this
     */
    public static function makePrivateStatic(string $name)
    {
        return self::makePrivate($name)->setAsStatic();
    }

    /**
     * Instantiate a new protected method.
     * 
     * @param   string $name
     * @return  $this
     */
    public static function makeProtected(string $name)
    {
        return (new self($name, false))->setProtected();
    }

    /**
     * Instantiate a new protected static method.
     * 
     * @param   string $name
     * @return  $this
     */
    public static function makeProtectedStatic(string $name)
    {
        return self::makeProtected($name)->setAsStatic();
    }

    /**
     * Instantiate a public constructor method.
     * 
     * @return  $this
     */
    public static function makeConstructor()
    {
        return self::makePublicConstructor();
    }

    /**
     * Instantiate a public constructor method.
     * 
     * @return  $this
     */
    public static function makePublicConstructor()
    {
        return self::makePublic('__construct');
    }

    /**
     * Instantiate a private constructor method.
     * 
     * @return  $this
     */
    public static function makePrivateConstructor()
    {
        return self::makePrivate('__construct');
    }

}