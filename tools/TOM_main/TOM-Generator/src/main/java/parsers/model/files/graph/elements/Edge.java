package parsers.model.files.graph.elements;
import org.jgrapht.graph.DefaultEdge;
import java.io.Serializable;

/**
 * @author raphaelrodrigues
 */
public class Edge extends DefaultEdge implements Serializable {

    private String dst;
    private Event event;

    public Edge(String dst, Event event) {
        this.dst = dst;
        this.event = event;
    }

    public Edge(Edge e) {
        this.dst = e.getDst();
        this.event = e.getEvent();
    }

    /**
     * @return the dst
     */
    public String getDst() {
        return dst;
    }

    /**
     * @param dst the dst to set
     */
    public void setDst(String dst) {
        this.dst = dst;
    }

    /**
     * @return the event
     */

    public Event getEvent() {
        return event;
    }

    /**
     * @param event the event to set
     */
    public void setEvent(Event event) {
        this.event = event;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) {
            return true;
        }
        if (o == null || getClass() != o.getClass()) {
            return false;
        }

        Edge edge = (Edge) o;

        if (dst != null ? !dst.equals(edge.dst) : edge.dst != null) {
            return false;
        }
        return event != null ? event.equals(edge.event) : edge.event == null;
    }
//
//    @Override
//    public int hashCode() {
//        int result = dst.hashCode();
//        result = 31 * result + event.hashCode();
//        return result;
//    }
}
