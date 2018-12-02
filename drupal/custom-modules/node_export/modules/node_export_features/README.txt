$Id: README.txt,v 1.1.2.1 2010/12/06 21:14:54 jasonleon Exp $

Node export features
--------------------

This module uses features and provides an additional functionality to import
node and do an node update if we find a match of node UUID. If the imported node
and a node already in the database have same UUID, then node is updated. One
can export nodes as features.
It also attempts to maintain node relationship between the nodes by exporting
the referenced node. But it bypasses access check for the referenced node. 