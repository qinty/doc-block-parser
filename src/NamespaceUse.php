<?php

namespace DocBlockParser;

class NamespaceUse
{
    protected $baseNamespace = '';
    protected $tokens        = [];
    protected $uses          = [];
    protected $buffer        = '';
    protected $listen        = false;

    /**
     * @param \ReflectionClass $oReflection
     *
     * @return NamespaceUse
     */
    public static function fromReflectionClass(\ReflectionClass $oReflection): NamespaceUse
    {
        $namespace = $oReflection->getNamespaceName();
        $contents  = file_get_contents($oReflection->getFileName());

        return new self($contents, $namespace);
    }

    public function __construct($contents, $baseNamespace)
    {
        $this->baseNamespace = $baseNamespace;
        $this->tokens        = token_get_all($contents);
    }

    public function getEntries(): array
    {
        return array_map(function ($entry) {
            $parts = explode(' as ', str_replace("\t", ' ', $entry));

            $fullClassName = trim($parts[0]);
            $alias         = array_key_exists(1, $parts) ? trim($parts[1]) : strrchr($fullClassName, '\\');

            return [
                'type'  => $fullClassName,
                'alias' => ($alias[0] === '\\') ? substr($alias, 1) : $alias,
            ];

        }, $this->getUsesFromTokens());
    }

    public function getFullClassName($type): string
    {
        foreach ($this->getEntries() as $use) {
            if ($use['alias'] === $type) {
                return $use['type'];
            }
        }

        $sample = Property::build($type);
        if ($type[0] !== '\\' && !$sample->isBasicType()) {
            $type = implode('\\', ['', $this->baseNamespace, $type]);
        }

        return $type;
    }

    /**
     * @return array
     */
    protected function getUsesFromTokens(): array
    {
        $this->resetTransientFlags();

        foreach ($this->tokens as $entry) {
            if ($this->listen) {
                $this->checkIfEndOfLine($entry);
                $this->checkIfAppendUseLine($entry);
            }

            $this->checkIfStartOfUseLine($entry);
        }

        return $this->uses;
    }

    protected function checkIfEndOfLine($entry): void
    {
        if (\is_string($entry) && $entry === ';') {
            $this->uses[] = $this->buffer;
            $this->buffer = '';
            $this->listen = false;
        }
    }

    protected function checkIfStartOfUseLine($entry): void
    {
        if (\is_array($entry) && $entry[1] === 'use') {
            $this->listen = true;
            $this->buffer = '';
        }
    }

    protected function checkIfAppendUseLine($entry): void
    {
        if (\is_array($entry) && \count($entry) >= 2) {
            $this->buffer .= $entry[1];
        }
    }

    protected function resetTransientFlags(): void
    {
        $this->uses   = [];
        $this->buffer = '';
        $this->listen = false;
    }
}
