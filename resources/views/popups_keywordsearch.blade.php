<STYLE>
    .blue{
        border: 2px solid blue;margin-left: 5px;margin-bottom: 5px;margin-right: 5px;margin-top: 5px;
    }
    .red{
        border: 1px solid red;
        margin-top: 10px;
    }
</STYLE>
<DIV CLASS="red">
    <?= printfile("popups/keywordsearch.blade.php"); ?>
    <P>Keyword Search:
    <TABLE BORDER="1">
        <THEAD>
            <TR>
                <TH>ID</TH><TH>Synonyms</TH><TH CLASS="hideme">Weight</TH><TH CLASS="hideme">Context</TH><TH>Actions</TH>
            </TR>
            <TR>
                <TD></TD>
                <TD><INPUT TYPE="TEXT" NAME="searchtext" ID="searchtext" TITLE="Do not use commas to delimit it, do not add a keyword that exists already"></TD>
                <TD CLASS="hideme">
                    <INPUT TYPE="NUMBER" MIN="1" MAX="5" VALUE="1" NAME="weight" ID="weight" TITLE="A weight of 5 counts as an item important enough to run a new search">
                </TD>
                <TD CLASS="hideme">
                    <SELECT ONCHANGE="keychange();" ID="keywordtype">
                        <OPTION VALUE="0">Unknown</OPTION>
                        <OPTION VALUE="1">Quantity</OPTION>
                        <OPTION VALUE="2">Size</OPTION>
                    </SELECT>
                </TD>
                <TD><BUTTON ONCLICK="search();" TITLE="Do not include synonyms in the search">Search</BUTTON></TD>
            </TR>
        </THEAD>
        <TBODY ID="searchbody"></TBODY>
    </TABLE>

    <P>Instructions on how to make a new keyword:
    <UL>
        <LI>If you search for a keyword and it is not found, you are given the option to create it</LI>
        <LI>When searching, only search for one keyword and not all it's synonyms.</LI>
        <LI>This step is important because you cannot add the same keyword multiple times as it will conflict with itself (the system will attempt to prevent this)</LI>
        <LI>Before clicking the 'Create' button, add all the synonyms for the keyword, separating them with spaces (punctuation and double spaces will be removed)</LI>
        <LI>Then set the weight, the higher the number means the keyword is more specific to that item. 5 being 100% specific, and will act as a new search term (if a search has multiple weight-5 keywords, each one will trigger a new search)</LI>
        <LI>Then set the context (if applicable) of the keyword. ie: Is it a quantity, or a size?</LI>
    </UL>

    <P>Notes:
    <UL>
        <LI>The system will only allow you to assign one quantity-type or size-type keyword to a menu item</LI>
        <LI>Assigning a keyword to the <I>Category</I> will assign it to every item in that category</LI>
        <LI>Likewise, unassigning a keyword from the <I>Category</I> will unassign it from every item in that category</LI>
    </UL>
</DIV>