<?php

namespace Graphite\Component\Stencil;

class Stencil
{
    /**
     * Current stencil version.
     * 
     * @var string
     */
    private $version = '1.0.1';

    /**
     * Filename of the file to be generated.
     * 
     * @var string
     */
    private $filename;

    /**
     * Namespace to be used.
     * 
     * @var string
     */
    private $namespace;

    /**
     * Classes that are needed to be imported.
     * 
     * @var array
     */
    private $imports = array();

    /**
     * If class is an abstract class.
     * 
     * @var bool
     */
    private $abstract = false;

    /**
     * Classname of the file to be generated.
     * 
     * @var string
     */
    private $classname;

    /**
     * Classname of the parent class.
     * 
     * @var string
     */
    private $extends;

    /**
     * Interfaces to be implemented.
     * 
     * @var array
     */
    private $implements = array();

    /**
     * Segments per each lines.
     * 
     * @var array
     */
    private $lines = array();

    /**
     * How default spaces in each line.
     * 
     * @var int
     */
    private $indention = 0;

    /**
     * Constructor the the stencil class.
     * 
     * @param   string $name
     * @return  void
     */
    public function __construct(string $name)
    {
        $this->filename     = str_to_pascal($name);
        $this->classname    = ucfirst($name);
    }

    /**
     * Set the number of spaces in each line.
     * 
     * @param   int $n
     * @return  $this
     */
    public function setIndention(int $n)
    {
        $this->indention = $n * 4;

        return $this;
    }

    /**
     * Set the current namespace of class.
     * 
     * @param   string $namespace
     * @return  $this
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = str_replace('/', '\\', $namespace);

        return $this;
    }

    /**
     * Return the current namespace.
     * 
     * @return  string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set as an abstract class.
     * 
     * @return  $this
     */
    public function setAsAbstract()
    {
        $this->abstract = true;
        
        return $this;
    }

    /**
     * Set classname of the file to be generated.
     * 
     * @param   string $class
     * @return  $this
     */
    public function setClassname(string $class)
    {
        $this->classname = str_to_pascal($class);

        return $this;
    }

    /**
     * Return classname of the PHP class.
     * 
     * @return  string
     */
    public function getClassname()
    {
        return $this->classname;
    }

    /**
     * Set parent class of the PHP file.
     * 
     * @param   string $extends
     * @return  $this
     */
    public function extends(string $extends)
    {
        $this->extends = str_replace('/', '\\', ucfirst($extends));

        return $this;
    }

    /**
     * Return parent class.
     * 
     * @return  string
     */
    public function getExtendedClass()
    {
        return $this->extends;
    }

    /**
     * Add new PHP class to be use.
     * 
     * @param   string $classname
     * @param   string $alias
     * @return  $this
     */
    public function use(string $classname, string $alias = null)
    {
        $this->imports[] = array(
            'classname'             => ucfirst($classname),
            'alias'                 => $alias,
        );

        return $this;
    }

    /**
     * Implement a set of interfaces in the class.
     * 
     * @param   mixed $interfaces
     * @return  $this
     */
    public function implement($interfaces)
    {
        if(is_string($interfaces))
        {
            $this->implements[] = ucfirst($interfaces);
        }
        else if(is_array($interfaces))
        {
            array_push($this->implements, ...$interfaces);
        }

        return $this;
    }

    /**
     * Append new line of raw string.
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

        $this->lines[] = str_repeat(' ', $indention) . $string;

        return $this;
    }

    /**
     * Add new class constant variable.
     * 
     * @param   string $name
     * @param   mixed $value
     * @param   int $indention
     * @return  $this
     */
    public function addConstant(string $name, $value, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        if(is_bool($value))
        {
            $value = $value ? "true" : "false";
        }
        else if(is_string($value))
        {
            $value = '"' . addslashes($value) . '"';
        }

        return $this->raw("const " . strtoupper($name) . " = " . $value . ";", $indention);
    }

    /**
     * Add new class variable.
     * 
     * @param   string $name
     * @param   string $visibility
     * @param   mixed $value
     * @param   bool $static
     * @param   int $indention
     * @return  $this
     */
    public function addVariable(string $name, string $visibility, $value = null, bool $static = false, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        $visibility = strtolower($visibility);

        if(in_array($visibility, ['public', 'private', 'protected']))
        {
            $template = $visibility;

            if($static)
            {
                $template .= ' static';
            }

            $template .= ' $' . str_to_snake($name);

            if(!is_null($value))
            {
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

                $template .= ' = ' . $value;
            }

            $template .= ";";

            $this->raw($template, $indention);
        }
        
        return $this;
    }

    /**
     * Add new public class variable.
     * 
     * @param   string $name
     * @param   mixed $value
     * @param   bool $static
     * @param   int $indention
     * @return  $this
     */
    public function addPublicVariable(string $name, $value = null, bool $static = false, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        return $this->addVariable($name, 'public', $value, $static, $indention);
    }

    /**
     * Add new public static variable.
     * 
     * @param   string $name
     * @param   mixed $value
     * @param   int $indention
     * @return  $this
     */
    public function addPublicStaticVariable(string $name, $value = null, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        return $this->addPublicVariable($name, $value, true, $indention);
    }

    /**
     * Add new public variable with null value.
     * 
     * @param   string $name
     * @param   bool $static
     * @param   int $indention
     * @return  $this
     */
    public function addNullPublicVariable(string $name, bool $static = false, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        return $this->addPublicVariable($name, null, $static, $indention);
    }

    /**
     * Add new private class variable.
     * 
     * @param   string $name
     * @param   mixed $value
     * @param   bool $static
     * @param   int $indention
     * @return  $this
     */
    public function addPrivateVariable(string $name, $value = null, bool $static = false, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        return $this->addVariable($name, 'private', $value, $static, $indention);
    }

    /**
     * Add new private static variable.
     * 
     * @param   string $name
     * @param   mixed $value
     * @param   int $indention
     * @return  $this
     */
    public function addPrivateStaticVariable(string $name, $value = null, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        return $this->addPrivateVariable($name, $value, true, $indention);
    }

    /**
     * Add new private variable with null value.
     * 
     * @param   string $name
     * @param   bool $static
     * @param   int $indention
     * @return  $this
     */
    public function addNullPrivateVariable(string $name, bool $static = false, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        return $this->addPrivateVariable($name, null, $static, $indention);
    }

    /**
     * Add new protected class variable.
     * 
     * @param   string $name
     * @param   mixed $value
     * @param   bool $static
     * @param   int $indention
     * @return  $this
     */
    public function addProtectedVariable(string $name, $value = null, bool $static = false, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        return $this->addVariable($name, 'protected', $value, $static, $indention);
    }

    /**
     * Add new protected static variable.
     * 
     * @param   string $name
     * @param   mixed $value
     * @param   int $indention
     * @return  $this
     */
    public function addProtectedStaticVariable(string $name, $value = null, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        return $this->addProtectedVariable($name, $value, true, $indention);
    }

    /**
     * Add new protected variable with null value.
     * 
     * @param   string $name
     * @param   bool $static
     * @param   int $indention
     * @return  $this
     */
    public function addNullProtectedVariable(string $name, bool $static = false, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        return $this->addProtectedVariable($name, null, $static, $indention);
    }

    /**
     * Add new empty line.
     * 
     * @param   int $n
     * @return  $this
     */
    public function lineBreak(int $n = 1)
    {
        for($i = 1; $i <= $n; $i++)
        {
            $this->lines[] = "";
        }

        return $this;
    }

    /**
     * Add new single line comment.
     * 
     * @param   string $message
     * @param   int $indention
     * @return  $this
     */
    public function addLineComment(string $message, int $indention = -1)
    {
        return $this->raw("// " . ucfirst($message), $indention);
    }

    /**
     * Add new class method function.
     * 
     * @param   \Graphite\Component\Stencil\Method $method
     * @param   int $indention
     * @return  $this
     */
    public function addMethod(Method $method, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        foreach($method->templates() as $template)
        {
            $this->raw($template, $indention);
        }

        return $this;
    }

    /**
     * Add new block comment.
     * 
     * @param   \Graphite\Component\Stencil\Comment $comment
     * @param   int $indention
     * @return  $this
     */
    public function addComment(Comment $comment, int $indention = -1)
    {
        if($indention < 0)
        {
            $indention = $this->indention;
        }

        foreach($comment->templates() as $template)
        {
            $this->raw($template, $indention);
        }

        return $this;
    }

    /**
     * Generate PHP class template.
     * 
     * @return  string
     */

    public function template()
    {
        $segments = array();

        // Always start with the php tag.
        $segments[] = '<?php ' . PHP_EOL;

        // Add namespace if provided.
        if(!is_null($this->namespace))
        {
            $segments[] = 'namespace ' . $this->namespace . ';' . PHP_EOL;
        }

        // Import all used PHP classes.
        if(!empty($this->imports))
        {
            foreach($this->imports as $import)
            {
                $template = "use " . $import['classname'];

                if(!is_null($import['alias']))
                {
                    $template .= " as " . ucfirst($import['alias']);
                }

                $template .= ";";

                $segments[] = $template;
            }

            $segments[] = "";
        }

        $template = '';

        // Set the class as an abstract class.
        if($this->abstract)
        {
            $template .= 'abstract ';
        }

        $template .= 'class ' . $this->classname;

        // Append the parent class if provided.
        if(!is_null($this->extends))
        {
            $template .= ' extends ' . $this->extends;
        }

        // Append implemented interfaces.
        if(!empty($this->implements))
        {
            $template .= ' implements ' . implode(', ', $this->implements);
        }

        $segments[] = $template;
        $segments[] = "{";
        
        // Add the body of the template.
        if(!empty($this->lines))
        {
            foreach($this->lines as $line)
            {
                $segments[] = $line;
            }
        }

        $segments[] = "}";
        
        return implode(PHP_EOL, $segments);
    }

    /**
     * Generate a new PHP file.
     * 
     * @param   string $path
     * @return  void
     */
    public function generate(string $path)
    {
        $filename = $path . ucfirst($this->filename) . '.php';
        
        if(!file_exists($filename))
        {
            $file = fopen($filename, 'w');
            fwrite($file, $this->template());
            fclose($file);
        }
    }

    /**
     * Return filename of the file to be generated.
     * 
     * @return  string
     */
    public function getFileName()
    {
        return $this->filename;
    }

    /**
     * Return current version.
     * 
     * @return  string
     */
    public function version()
    {
        return $this->version;
    }

}