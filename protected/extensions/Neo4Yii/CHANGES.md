#CHANGES:

###March-12-2012:
1. Allowing the usage of gremlin parameters which vastly improves a lot of queries that can now be cached on the server side: https://github.com/Haensel/Neo4Yii/commit/bea38df6a89c6217c699a48c025c1822557f43ce, https://github.com/Haensel/Neo4Yii/commit/b55897457ea653b496af308154c80b5def3e22cf,https://github.com/Haensel/Neo4Yii/commit/90ec04a485f21a1f85e3e8b3eec0eb758ac11244

2. fixed a bug returning an array when using findByExactIndexEntry: https://github.com/Haensel/Neo4Yii/commit/b55897457ea653b496af308154c80b5def3e22cf


###March-09-2012:

1. getId() returns integer values:
https://github.com/Haensel/Neo4Yii/commit/456da853afddd18aebdf9ad7c1c1ae3792ae7290

2. Revision of ENeo4jBatchTransaction. It directly uses neo4j's batch api so you will have to
take care of validation etc. All operations will have to be "handcrafted" either by addOperation()
or a helper method like saveNode(): https://github.com/Haensel/Neo4Yii/commit/c27e7cc716f36bf2f48f7f430f6903f43ed6c65f

3.FindAll() methods query the default index of a property container now to  allow very fast queries even on large result sets.

4.Index query results can now be limited