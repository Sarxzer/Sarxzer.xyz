<?

    function htmlspecialcharsExceptFencedCodeBlocks($text) {
        // Split the text into parts that are inside fenced code blocks and parts that are not
        $parts = preg_split('/(```[^`]*```)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        // Apply htmlspecialchars to the parts that are not inside fenced code blocks
        foreach ($parts as $i => $part) {
            if ($i % 2 == 0) {  // Every second part is outside a fenced code block
                $parts[$i] = htmlspecialchars($part);
            }
        }

        // Reassemble the text
        return implode('', $parts);
    }


?>