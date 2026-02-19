package parsers.model.files;

import org.jgrapht.Graph;

/**
 * @author raphael
 */
public interface IParserStrategy {

    Graph parsing(String path);

    String getModelName();

    String getInitialNode();

    String getFinalNode();

}
