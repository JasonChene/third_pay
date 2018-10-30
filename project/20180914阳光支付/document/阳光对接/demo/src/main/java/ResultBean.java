import java.io.Serializable;

public class ResultBean implements Serializable {
    private String msg;
    private String status;
    private Object data;

    public static boolean isOk(ResultBean resultBean){
        return resultBean.getStatus().equals("OK");
    }
    public static ResultBean getError(String msg){
        ResultBean resultBean = new ResultBean();
        resultBean.msg = msg;
        resultBean.status = "ERROR";
        return resultBean;
    }
    public static ResultBean getErrorNoToken(String msg){
        ResultBean resultBean = new ResultBean();
        resultBean.msg = msg;
        resultBean.status = "ERROR_NO_TOKEN";
        return resultBean;
    }
    public static ResultBean getSuccess(Object data){
        ResultBean resultBean = new ResultBean();
        resultBean.msg = "OK";
        resultBean.status = "OK";
        resultBean.data = data;
        return resultBean;
    }

    public String getMsg() {
        return msg;
    }

    public void setMsg(String msg) {
        this.msg = msg;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public Object getData() {
        return data;
    }

    public void setData(Object data) {
        this.data = data;
    }

    @Override
    public String toString() {
        return "ResultBean{" +
                "msg='" + msg + '\'' +
                ", status=" + status +
                ", data=" + data +
                '}';
    }
}
