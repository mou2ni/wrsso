<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/12/2018
 * Time: 16:59
 */

namespace App\DoctrineExtensions\Query\MySql;


use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\QueryException;

/**
 * CastFunction ::= "CAST" "(" ArithmeticPrimary ")"
 */
class Cast extends FunctionNode
{
    public $dateExpression = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        try {
            $parser->match(Lexer::T_IDENTIFIER);
        } catch (QueryException $e) {
        } // (2)
        try {
            $parser->match(Lexer::T_OPEN_PARENTHESIS);
        } catch (QueryException $e) {
        } // (3)
        $this->dateExpression = $parser->ArithmeticPrimary(); // (4)
        //$parser->match(Lexer::T_COMMA); // (5)
        //$this->secondDateExpression = $parser->ArithmeticPrimary(); // (6)
        try {
            $parser->match(Lexer::T_CLOSE_PARENTHESIS);
        } catch (QueryException $e) {
        } // (3)
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'DATEDIFF(' .
            $this->dateExpression->dispatch($sqlWalker) .
            ')'; // (7)
    }

}