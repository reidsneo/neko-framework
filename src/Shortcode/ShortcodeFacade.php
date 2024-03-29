<?php
namespace Neko\Framework\Shortcode;

use Neko\Framework\Shortcode\EventContainer\EventContainer;
use Neko\Framework\Shortcode\HandlerContainer\HandlerContainer;
use Neko\Framework\Shortcode\HandlerContainer\HandlerContainerInterface;
use Neko\Framework\Shortcode\Parser\ParserInterface;
use Neko\Framework\Shortcode\Parser\RegularParser;
use Neko\Framework\Shortcode\Processor\Processor;
use Neko\Framework\Shortcode\Processor\ProcessorInterface;
use Neko\Framework\Shortcode\Serializer\JsonSerializer;
use Neko\Framework\Shortcode\Serializer\SerializerInterface;
use Neko\Framework\Shortcode\Serializer\TextSerializer;
use Neko\Framework\Shortcode\Serializer\XmlSerializer;
use Neko\Framework\Shortcode\Serializer\YamlSerializer;
use Neko\Framework\Shortcode\Shortcode\ParsedShortcodeInterface;
use Neko\Framework\Shortcode\Shortcode\ShortcodeInterface;
use Neko\Framework\Shortcode\Syntax\CommonSyntax;
use Neko\Framework\Shortcode\Syntax\SyntaxInterface;

/**
 * @author Tomasz Kowalczyk <tomasz@kowalczyk.cc>
 */
class ShortcodeFacade
{
    /** @var ProcessorInterface */
    private $processor;
    /** @var ParserInterface */
    private $parser;
    /** @var SyntaxInterface */
    private $syntax;

    /** @var HandlerContainer */
    private $handlers;
    /** @var EventContainer */
    private $events;

    /** @var SerializerInterface */
    private $textSerializer;
    /** @var SerializerInterface */
    private $jsonSerializer;
    /** @var SerializerInterface */
    private $xmlSerializer;
    /** @var SerializerInterface */
    private $yamlSerializer;

    public function __construct()
    {
        $this->syntax = new CommonSyntax();
        $this->handlers = new HandlerContainer();
        $this->events = new EventContainer();

        $this->parser = new RegularParser($this->syntax);
        $this->rebuildProcessor();

        $this->textSerializer = new TextSerializer();
        $this->jsonSerializer = new JsonSerializer();
        $this->yamlSerializer = new YamlSerializer();
        $this->xmlSerializer = new XmlSerializer();
    }

    /**
     * @deprecated use constructor and customize using exposed methods
     * @return self
     */
    public static function create(HandlerContainerInterface $handlers, SyntaxInterface $syntax)
    {
        $self = new self();

        /** @psalm-suppress PropertyTypeCoercion */
        $self->handlers = $handlers;
        $self->syntax = $syntax;
        $self->rebuildProcessor();

        return $self;
    }

    /** @return void */
    private function rebuildProcessor()
    {
        $this->processor = new Processor($this->parser, $this->handlers);
        $this->processor = $this->processor->withEventContainer($this->events);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function process($text)
    {
        return $this->processor->process($text);
    }

    /**
     * @param string $text
     *
     * @return ParsedShortcodeInterface[]
     */
    public function parse($text)
    {
        return $this->parser->parse($text);
    }

    /** @return $this */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
        $this->rebuildProcessor();

        return $this;
    }

    /**
     * @param string $name
     * @psalm-param callable(ShortcodeInterface):string $handler
     *
     * @return $this
     */
    public function addHandler($name, $handler)
    {
        $this->handlers->add($name, $handler);

        return $this;
    }

    /**
     * @param string $alias
     * @param string $name
     *
     * @return $this
     */
    public function addHandlerAlias($alias, $name)
    {
        $this->handlers->addAlias($alias, $name);

        return $this;
    }

    /**
     * @param string $name
     * @param callable $handler
     *
     * @return $this
     */
    public function addEventHandler($name, $handler)
    {
        $this->events->addListener($name, $handler);

        return $this;
    }

    /* --- SERIALIZATION --------------------------------------------------- */

    /**
     * @param string $format
     *
     * @return string
     */
    public function serialize(ShortcodeInterface $shortcode, $format)
    {
        switch($format) {
            case 'text': return $this->textSerializer->serialize($shortcode);
            case 'json': return $this->jsonSerializer->serialize($shortcode);
            case 'yaml': return $this->yamlSerializer->serialize($shortcode);
            case 'xml': return $this->xmlSerializer->serialize($shortcode);
            default: throw new \InvalidArgumentException(sprintf('Invalid serialization format %s!', $format));
        }
    }

    /**
     * @param string $text
     * @param string $format
     *
     * @return ShortcodeInterface
     */
    public function unserialize($text, $format)
    {
        switch($format) {
            case 'text': return $this->textSerializer->unserialize($text);
            case 'json': return $this->jsonSerializer->unserialize($text);
            case 'yaml': return $this->yamlSerializer->unserialize($text);
            case 'xml': return $this->xmlSerializer->unserialize($text);
            default: throw new \InvalidArgumentException(sprintf('Invalid unserialization format %s!', $format));
        }
    }

    /**
     * @deprecated use serialize($shortcode, $format)
     * @return string
     */
    public function serializeToText(ShortcodeInterface $s) { return $this->serialize($s, 'text'); }

    /**
     * @deprecated use serialize($shortcode, $format)
     * @return string
     */
    public function serializeToJson(ShortcodeInterface $s) { return $this->serialize($s, 'json'); }

    /**
     * @deprecated use serialize($shortcode, $format)
     * @param string $text
     *
     * @return ShortcodeInterface
     */
    public function unserializeFromText($text) { return $this->unserialize($text, 'text'); }

    /**
     * @deprecated use serialize($shortcode, $format)
     * @param string $text
     *
     * @return ShortcodeInterface
     */
    public function unserializeFromJson($text) { return $this->unserialize($text, 'json'); }
}
