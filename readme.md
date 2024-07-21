# SOLID 和設計模式說明
## 單一職責原則（SRP）：OrderRequest 負責驗證請求數據，OrderService 負責業務邏輯。
## 開放封閉原則（OCP）：可以通過擴展 OrderService 來添加新功能，而不需要修改其內部代碼。
## 里氏替換原則（LSP）：可以替換 OrderService 的實現，並且不會影響 OrderController 的工作。
## 接口隔離原則（ISP）：每個類只依賴其所需的功能（例如，OrderService 只處理訂單的檢查和轉換）。
## 依賴倒置原則（DIP）：OrderController 依賴於 OrderService 的抽象（接口）而不是具體實現。


#資料庫測驗

##題目一
WITH top_ten_orders AS (
    SELECT 
        o.bnb_id,
        SUM(o.amount) AS may_amount
    FROM 
        orders o
    WHERE 
        o.created_at >= '2023-05-01 00:00:00'
        AND o.created_at < '2023-06-01 00:00:00'
		AND o.currency = 'TWD'
    GROUP BY 
        o.bnb_id
    ORDER BY 
        may_amount DESC
    LIMIT 10
)
SELECT 
    t.bnb_id,
    b.name AS bnb_name,
    t.may_amount
FROM 
    top_ten_orders t
JOIN 
    bnbs b ON t.bnb_id = b.id;
	
##題目二
1.加index
CREATE INDEX idx_orders_created_at_currency ON orders (created_at, currency);
CREATE INDEX idx_orders_bnb_id ON orders (bnb_id);
2.看執行計畫
看索引是否有按預期的吃到，type欄是否有出現ALL，
possible_keys欄有無建議加的索引來補強。
