<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
    <xsl:param name="randomselect" select="php:functionString('rand',1,8)" />
    <xsl:output omit-xml-declaration="yes" />
    <xsl:template match="/">

        <xsl:apply-templates select="new_books/record[position()= $randomselect]">
        </xsl:apply-templates>

    </xsl:template>


    <xsl:template match="new_books/record">

        <xsl:choose>
            <xsl:when test="isbn">
                <a>
                    <xsl:attribute name="href">
                        <xsl:text>http://catalog.lib.utexas.edu/search/?searchtype=.&amp;searcharg=</xsl:text>
                        <xsl:value-of select="record_id" />
                    </xsl:attribute>
                    <img>
                    <xsl:attribute name="src">
                        <xsl:text>http://www.lib.utexas.edu/amazon_check/image-test.php?spotlight=yes&amp;isbn=</xsl:text>
                        <!--<xsl:value-of select="substring(normalize-space(isbn),1,10)"/>-->
                        <xsl:value-of select="php:function('isbn_split',string(isbn))" />
                    </xsl:attribute>
                    <xsl:attribute name="height">
                        <xsl:text>115</xsl:text>
                    </xsl:attribute>
                    <xsl:attribute name="alt">
                        <xsl:text></xsl:text>
                    </xsl:attribute>
                    <xsl:attribute name="class">
                        <xsl:text>bookcover</xsl:text>
                    </xsl:attribute>
                    </img>
                </a>
            </xsl:when>
            <xsl:otherwise>
                <a>
                    <xsl:attribute name="href">
                        <xsl:text>http://catalog.lib.utexas.edu/search/?searchtype=.&amp;searcharg=</xsl:text>
                        <xsl:value-of select="record_id" />
                    </xsl:attribute>
                    <img>
                    <xsl:attribute name="src">
                        <xsl:text>http://www.lib.utexas.edu/images/firstspotlight/default-recent-arrivals.png</xsl:text>
                    </xsl:attribute>
                    <xsl:attribute name="height">
                        <xsl:text>115</xsl:text>
                    </xsl:attribute>
                    <xsl:attribute name="alt">
                        <xsl:text></xsl:text>
                    </xsl:attribute>
                    <xsl:attribute name="class">
                        <xsl:text>bookcover</xsl:text>
                    </xsl:attribute>
                    </img>
                </a>
                <br />
            </xsl:otherwise>
        </xsl:choose>
        <p>
            <xsl:attribute name="class">
                <xsl:text>spotlightdesc</xsl:text>
            </xsl:attribute>
            <a>
                <xsl:attribute name="href">
                    <xsl:text>http://catalog.lib.utexas.edu/search/?searchtype=.&amp;searcharg=</xsl:text>
                    <xsl:value-of select="record_id" />
                </xsl:attribute>
                <xsl:variable name="titlelength" select="string-length(normalize-space(title))" />
                <xsl:choose>
                    <!-- if title is 74 characters or longer, display the first 74 characters and add an ellipsis -->
                    <xsl:when test="$titlelength &gt;= 74">
                        <!--<xsl:value-of select="substring(normalize-space(title),1,74)" disable-output-escaping="yes"  
/>-->
                        <!--<xsl:value-of select="php:functionString('titleformat', title)" disable-output-escaping="yes" 
/>-->
                        <xsl:value-of select="substring(php:function('recentarrivals_title',string(title)),1,74)" />
                        <xsl:text>...</xsl:text>
                    </xsl:when>
                    <!-- if title is shorter than 74 characters, display it unmodified -->
                    <xsl:otherwise>
                        <!--<xsl:value-of select="normalize-space(title)" disable-output-escaping="yes" />-->
                        <!--<xsl:value-of select="php:functionString('titleformat', title)" />-->
                        <xsl:value-of select="php:function('recentarrivals_title',string(title))" />
                    </xsl:otherwise>
                </xsl:choose>
            </a>
            <br />
            <br />
            <xsl:choose>
                <xsl:when test="author">
                    <a>
                        <xsl:attribute name="href">
                            <xsl:text>http://catalog.lib.utexas.edu/search/a?SEARCH=</xsl:text>
                            <xsl:value-of select="php:function('urlencode',string(author))" disable-output-escaping="yes" />
                        </xsl:attribute>
                        <!--<xsl:value-of select="author"/>-->
                        <xsl:variable name="authorlength" select="string-length(normalize-space(author))" />
                        <xsl:choose>
                            <!-- if author is 54 characters or longer, display the first 54 characters and add an ellipsis -->
                            <xsl:when test="$authorlength &gt;= 54">
                                <xsl:value-of select="substring(normalize-space(author),1,54)" disable-output-escaping="yes" />
                                <xsl:text>...</xsl:text>
                            </xsl:when>
                            <!--if author is shorter than 54 characters, display it unmodified -->
                            <xsl:otherwise>
                                <xsl:value-of select="normalize-space(author)" disable-output-escaping="yes" />
                            </xsl:otherwise>
                        </xsl:choose>
                    </a>
                    <br />
                    <br />

                </xsl:when>
            </xsl:choose>

            <!--<xsl:variable name="desclength" select="string-length(normalize-space(pub_info))"/>
      <xsl:choose>-->
            <!-- if description is 54 characters or longer, display the first 54 characters and add an ellipsis -->
            <!--<xsl:when test="$desclength &gt;= 54">
          <xsl:value-of select="substring(normalize-space(pub_info),1,54)" disable-output-escaping="yes" />
          <xsl:text> ...</xsl:text>
        </xsl:when>-->
            <!-- if description is shorter than 54 characters, display it unmodified -->
            <!--<xsl:otherwise>
          <xsl:value-of select="normalize-space(pub_info)" disable-output-escaping="yes" />
        </xsl:otherwise>
      </xsl:choose> 
      <br /><br />-->
            <!--<xsl:value-of select="$location"/><br />-->
        </p>
    </xsl:template>

</xsl:stylesheet>