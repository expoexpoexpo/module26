<?php

class MetaCleaner implements Iterator
{
    private array $lines;
    private array $namesToRemove;
    private int $position = 0;
    private array $removedTags = [];

    public function __construct(array $lines, array $namesToRemove)
    {
        $this->lines = $lines;
        $this->namesToRemove = $namesToRemove;
    }

    public function current(): string
    {
        $line = $this->lines[$this->position];

        if (preg_match_all('/<meta\b[^>]*>/i', $line, $matches)) {
            foreach ($matches[0] as $metaTag) {
                foreach ($this->namesToRemove as $name) {
                    if (preg_match('/name=["\']?' . preg_quote($name, '/') . '["\']?/i', $metaTag)) {
                        $this->removedTags[] = $metaTag;
                        $line = str_replace($metaTag, '', $line);
                        break;
                    }
                }
            }
        }

        return $line;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->lines[$this->position]);
    }

    public function getRemovedTags(): array
    {
        return $this->removedTags;
    }
}

$lines = file('index.html');
$namesToRemove = ['title', 'description', 'keywords'];

$cleaner = new MetaCleaner($lines, $namesToRemove);

$cleanedHtml = '';
foreach ($cleaner as $line) {
    $cleanedHtml .= $line;
}
