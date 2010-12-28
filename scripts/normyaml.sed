#!/bin/sed -f
s/\(.*\)Model\(.*\):/\1_Model_\2:/
s/tableName: \(.*\)_Model_\(.*\)/tableName: \L\1_\L\2/
s/class: \(.*\)Model\(.*\)/class: \1_Model_\2/
s/foreignAlias: \(.*\)_Model_\(.*\)/foreignAlias: \2/
s/clob(65535)/clob/
