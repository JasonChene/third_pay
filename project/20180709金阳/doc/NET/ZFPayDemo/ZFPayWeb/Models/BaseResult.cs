namespace ZFPayWeb.Models
{
    /// <summary>
    /// 返回信息(基类)
    /// </summary>
    public class BaseResult<T> : BaseResult where T : class
    {
        /// <summary>
        /// shuju
        /// </summary>
        public T data { get; set; }

    }

    /// <summary>
    /// 返回信息
    /// </summary>
    public class BaseResult
    {
        /// <summary>
        /// 返回码
        /// </summary>
        public int rspCode { get; set; }

        /// <summary>
        /// 返回信息
        /// </summary>
        public string rspMsg { get; set; }
    }
}