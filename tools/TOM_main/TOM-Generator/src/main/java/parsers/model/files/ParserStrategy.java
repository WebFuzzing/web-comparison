package parsers.model.files;

import org.jgrapht.Graph;

/**
 * @author raphael
 */
public class ParserStrategy {
    private final IParserStrategy strategy;

    public ParserStrategy(IParserStrategy strategy) {
        this.strategy = strategy;
    }

    public Graph executeStrategy(String path) {
        return this.strategy.parsing(path);
    }

    public String getModelName() {
        return this.strategy.getModelName();
    }

    public String getInitialNode() {
        return this.strategy.getInitialNode();
    }

    public String getFinalNode() {
        return this.strategy.getFinalNode();
    }

}
