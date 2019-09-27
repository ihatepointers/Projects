using System;
using System.ServiceModel;
using System.Data.SqlClient;
using System.Collections.Generic;
using System.IO;

namespace GettingStartedLib
{
    [ServiceBehavior(AddressFilterMode = AddressFilterMode.Any)]
    public class CalculatorService : ICalculator
    {
        static string connectionString = "Data Source= ;Initial Catalog= ;User ID= ;Password= ";
        static SqlConnection sqlConnection = new SqlConnection(connectionString);

        static string connectionString2 = "Data Source= ;Initial Catalog= ;User ID= ;Password= ";
        static SqlConnection sqlConnection2 = new SqlConnection(connectionString2);

        static int stage = 0;

        static string orderNo = "";
        static int logicalRef = -1;
        static string barcode = "";
        static int itemIndex = 0;
        static string fischeNo = "";

        static List<(string, string, string, int)> orders = new List<(string, string, string, int)>();
        static List<(int, string, string, int, string)> items = new List<(int, string, string, int, string)>();

        static List<(int, string, string, int, string)> tmpItems = new List<(int, string, string, int, string)>();


        public double Add(double n1, double n2)
        {
            double result = n1 + n2;
            Console.WriteLine("Received Add({0},{1})", n1, n2);
            Console.WriteLine("Return: {0}", result);
            return result;
        }

        public double Subtract(double n1, double n2)
        {
            double result = n1 - n2;
            Console.WriteLine("Received Subtract({0},{1})", n1, n2);
            Console.WriteLine("Return: {0}", result);
            return result;
        }

        public double Multiply(double n1, double n2)
        {
            double result = n1 * n2;
            Console.WriteLine("Received Multiply({0},{1})", n1, n2);
            Console.WriteLine("Return: {0}", result);
            return result;
        }

        public double Divide(double n1, double n2)
        {
            double result = n1 / n2;
            Console.WriteLine("Received Divide({0},{1})", n1, n2);
            Console.WriteLine("Return: {0}", result);
            return result;
        }

        public String GetText()
        {
            return "Hello";
        }


        public String MirrorText(string text)
        {
            return text;
        }

        public List<string> predictSpeech(byte[] soundData)
        {
            var responseTextList = new List<string>();

            Random rnd = new Random();
            int randomNumber = rnd.Next(1, 999999);

            string fileName = "path/to/predictions" + randomNumber.ToString() + ".wav";

            File.WriteAllBytes(fileName, soundData);

            responseTextList.Add("Dosya kaydedildi");


            return responseTextList;
        }

        public List<string> ProcessText(string text)
        {
            var returnTextList = new List<string>();

            if(Equals(text.ToLower(), "bir"))
            {
                text = "1";
            }

            
            if ((CalculateSimilarity(text.ToLower(), "baştan başla") > 0.5) || (CalculateSimilarity(text.ToLower(), "siparişleri listele") > 0.5))
            {
                stage = 0;

            }else if ((CalculateSimilarity(text.ToLower(), "siparişi tamamla") > 0.7) || (CalculateSimilarity(text.ToLower(), "tamamla") > 0.4))
            {
                stage = 5;
            }


            switch (stage)
            {
                case 0:
                    Console.WriteLine("Case 0");
                    //sipariş listele

                    itemIndex = 0;
                    fischeNo = "";

                    orders = new List<(string, string, string, int)>();
                    items = new List<(int, string, string, int, string)>();

                    tmpItems = new List<(int, string, string, int, string)>();



                    try
                    {
                        string sql = "SELECT ORF.FICHENO, CL.CODE, CL.DEFINITION_, (SELECT COUNT(LOGICALREF) " +
                            "FROM LG_012_01_ORFLINE WHERE ORDFICHEREF = ORF.LOGICALREF AND CLOSED = 0 AND AMOUNT -SHIPPEDAMOUNT > 0) FROM LG_012_01_ORFICHE ORF LEFT OUTER JOIN LG_012_CLCARD CL ON CL.LOGICALREF = ORF.CLIENTREF " +
                            "WHERE ORF.TRCODE = 1 AND(SELECT COUNT(LOGICALREF) FROM LG_012_01_ORFLINE WHERE ORDFICHEREF = ORF.LOGICALREF AND CLOSED = 0 AND AMOUNT - SHIPPEDAMOUNT > 0 AND LINETYPE = 0) > 0";

                        sqlConnection.Open();
                        SqlCommand command = new SqlCommand(sql, sqlConnection);
                        SqlDataReader dataReader = command.ExecuteReader();
                        returnTextList.Add("0");
                        returnTextList.Add("Sipariş numarası seç");
                        while (dataReader.Read())
                        {

                            orders.Add((dataReader.GetValue(0).ToString(), dataReader.GetValue(1).ToString(), dataReader.GetValue(2).ToString(), Convert.ToInt32(dataReader.GetValue(3).ToString())));

                            returnTextList.Add((dataReader.GetValue(0).ToString() + "|" + dataReader.GetValue(1).ToString() + "|" + dataReader.GetValue(2).ToString() + "|" + dataReader.GetValue(3).ToString()));
                        }
                        
                        dataReader.Close();
                        command.Dispose();
                        sqlConnection.Close();
                        stage = 1;
                    }
                    catch (Exception ex)
                    {
                        returnTextList.Add("0");
                        returnTextList.Add(ex.ToString());
                        stage = 0;
                    }
                    
                    break;

                case 1:
                    Console.WriteLine("Case 1");

                    int index = -1;
                    int counter = 0;

                    if (Int32.TryParse(text, out index))
                    {
                        try
                        {

                            fischeNo = orders[index].Item1.ToString();
                            returnTextList.Add("1");
                            returnTextList.Add("lRef kodunu oku");

                            string sql = "SELECT IT.LOGICALREF AS URUN_REFERANSI, IT.CODE, IT.NAME, ORL.AMOUNT - ORL.SHIPPEDAMOUNT AS MIKTAR, (SELECT CODE FROM LG_012_UNITSETL USL WHERE USL.LOGICALREF = UOMREF AND USL.UNITSETREF = USREF) AS BIRIM " +
                                           "FROM LG_012_01_ORFICHE ORF LEFT OUTER JOIN LG_012_01_ORFLINE ORL ON ORL.ORDFICHEREF = ORF.LOGICALREF LEFT OUTER JOIN LG_012_ITEMS IT ON IT.LOGICALREF = ORL.STOCKREF " +
                                            "WHERE ORF.FICHENO = '" + fischeNo + "' AND ORF.TRCODE = 1 AND ORL.LINETYPE = 0 AND ORL.AMOUNT - ORL.SHIPPEDAMOUNT > 0";


                            sqlConnection.Open();
                            SqlCommand command = new SqlCommand(sql, sqlConnection);
                            SqlDataReader dataReader = command.ExecuteReader();
                            while (dataReader.Read())
                            {
                                returnTextList.Add((dataReader.GetValue(0).ToString() + "|" + dataReader.GetValue(1).ToString() + "|" + dataReader.GetValue(2).ToString() + "|" + dataReader.GetValue(3).ToString() +"|"+ dataReader.GetValue(4).ToString()));
                                items.Add((Int32.Parse(dataReader.GetValue(0).ToString()), dataReader.GetValue(1).ToString(), dataReader.GetValue(2).ToString(), Int32.Parse(dataReader.GetValue(3).ToString()), dataReader.GetValue(4).ToString()));
                            }
                            dataReader.Close();
                            command.Dispose();
                            sqlConnection.Close();
                            stage = 2;
                        }
                        catch (Exception ex)
                        {
                            returnTextList.Add("0");
                            returnTextList.Add("Anlamadım");

                            stage = 0;
                        }
                    }
                    break;


                case 2:
                    int lRef;
                    if (Int32.TryParse(text, out lRef))
                    {
                        itemIndex = 0;

                        while((items[itemIndex].Item1 != lRef) && (itemIndex < items.Count))
                        {
                            itemIndex++;
                        }

                        if(itemIndex == items.Count)
                        {
                            returnTextList.Add("2");
                            returnTextList.Add("Kod hatalı");
                            returnTextList.Add("-1");
                            stage = 2;
                        }
                        else
                        {

                            returnTextList.Add("3");
                            returnTextList.Add(text  + " numaralı ürün: " + items[itemIndex].Item3.ToLower() + ". " +  items[itemIndex].Item4.ToString() + " " + items[itemIndex].Item5.ToLower() + ". Onay?");
                            returnTextList.Add(itemIndex.ToString());
                            stage = 3;
                        }
                    }
                    else
                    {
                        returnTextList.Add("2");
                        returnTextList.Add("Rakamlar anlaşılamadı");
                        returnTextList.Add("-1");
                        stage = 2;
                    }
                        break;


                case 3:

                    if (CalculateSimilarity(text.ToLower(), "evet") > 0.4)
                    {
                        tmpItems.Add(items[itemIndex]);

                        if(tmpItems.Count == items.Count)
                        {
                            returnTextList.Add("4");
                            returnTextList.Add("Toplama onaylandı. Siparişi tamamla.");
                            returnTextList.Add(itemIndex.ToString());
                            stage = 5;
                        }
                        else
                        {

                            returnTextList.Add("2");
                            returnTextList.Add("Onaylandı. Bir sonraki ürün.");
                            returnTextList.Add(itemIndex.ToString());

                            stage = 2;

                        }
                        
                    }
                    else if((CalculateSimilarity(text.ToLower(), "hayır") > 0.5) || (CalculateSimilarity(text.ToLower(), "eksik") > 0.5))
                    {
                        returnTextList.Add("3");
                        returnTextList.Add("Yeni ürün adedi.");
                        returnTextList.Add("-1");
                        stage = 4;
                    }
                    else
                    {
                        returnTextList.Add("3");
                        returnTextList.Add("Anlamadım.");
                        returnTextList.Add("-1");
                        stage = 3;
                    }
                    break;


                case 4:
                    int customAmount;
                    if (Int32.TryParse(text, out customAmount))
                    {

                        var tmpItem = items[itemIndex];
                        if(customAmount <= items[itemIndex].Item4)
                        {
                            tmpItem.Item4 = customAmount;

                            tmpItems.Add(tmpItem);

                            if (tmpItems.Count == items.Count)
                            {
                                returnTextList.Add("4");
                                returnTextList.Add(customAmount.ToString() + " adet ürün alındı. Siparişi tamamlayın.");
                                returnTextList.Add(itemIndex.ToString());
                                returnTextList.Add(customAmount.ToString());
                                stage = 5;

                            }
                            else
                            {
                                returnTextList.Add("4");
                                returnTextList.Add(customAmount.ToString() + " alındı. Bir sonraki ürün");
                                returnTextList.Add(itemIndex.ToString());
                                returnTextList.Add(customAmount.ToString());
                                stage = 2;
                            }

                        }
                        else
                        {
                            returnTextList.Add("4");
                            returnTextList.Add("Daha düşük bir ürün miktarı söyleyin.");
                            returnTextList.Add("-1");
                            returnTextList.Add("-1");
                            stage = 4;

                        }

                        

                    }
                    else
                    {

                        returnTextList.Add("4");
                        returnTextList.Add("Anlamadım");
                        returnTextList.Add("-1");
                        returnTextList.Add("-1");
                        stage = 4;
                    }
                        break;


                case 5:
                    int fail = 0;
                    for(int i = 0; i < tmpItems.Count; i++)
                    {
                        string sql = "";
                        try
                        {
                            sql = "INSERT INTO ITEMS " +
                                            "VALUES('" + fischeNo + "', '" + tmpItems[i].Item1.ToString() + "', '" + tmpItems[i].Item2 + "', '" + tmpItems[i].Item3 + "', '" + tmpItems[i].Item4.ToString() + "', '" + tmpItems[i].Item5 + "')";
                            sqlConnection2.Open();
                            SqlCommand command = new SqlCommand(sql, sqlConnection2);
                            SqlDataReader dataReader = command.ExecuteReader();
                            dataReader.Close();
                            command.Dispose();
                            sqlConnection2.Close();
                        }
                        catch (Exception ex)
                        {
                            if(fail == 0)
                            {
                                fail = 1;
                                returnTextList.Add(ex.ToString());
                                returnTextList.Add(sql);
                            }
                        }
                    }

                    if(fail == 0)
                    {
                        returnTextList.Add("0");
                        returnTextList.Add("Ürünler veri tabanına kaydedildi. Yeni bir sipariş başlatabilirsiniz");
                        stage = 0;
                    }
                    
                    break;

                default:
                    break;



            }
            return returnTextList;
        }



        double CalculateSimilarity(string source, string target)
        {
            if ((source == null) || (target == null)) return 0.0;
            if ((source.Length == 0) || (target.Length == 0)) return 0.0;
            if (source == target) return 1.0;

            int stepsToSame = LevenshteinDistance(source, target);
            return (1.0 - ((double)stepsToSame / (double)Math.Max(source.Length, target.Length)));
        }


        public static int LevenshteinDistance(string s, string t)
        {
            int n = s.Length;
            int m = t.Length;
            int[,] d = new int[n + 1, m + 1];

            if(string.Equals(s, t))
            {
                return 1;
            }
            else
            {

                // Step 1
                if (n == 0)
                {
                    return m;
                }

                if (m == 0)
                {
                    return n;
                }

                // Step 2
                for (int i = 0; i <= n; d[i, 0] = i++)
                {
                }

                for (int j = 0; j <= m; d[0, j] = j++)
                {
                }

                // Step 3
                for (int i = 1; i <= n; i++)
                {
                    //Step 4
                    for (int j = 1; j <= m; j++)
                    {
                        // Step 5
                        int cost = (t[j - 1] == s[i - 1]) ? 0 : 1;

                        // Step 6
                        d[i, j] = Math.Min(
                            Math.Min(d[i - 1, j] + 1, d[i, j - 1] + 1),
                            d[i - 1, j - 1] + cost);
                    }
                }
                // Step 7
                return d[n, m];


            }

            
        }

    }
}