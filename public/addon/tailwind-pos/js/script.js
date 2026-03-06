function initApp() {
  const boot = window.posBoot || {};
  const fallbackImages = [
    "img/beef-burger.png",
    "img/choco-glaze-donut-peanut.png",
    "img/choco-glaze-donut.png",
    "img/cinnamon-roll.png",
    "img/coffee-latte.png",
    "img/croissant.png",
    "img/ice-chocolate.png",
    "img/ice-tea.png",
    "img/matcha-latte.png",
    "img/red-glaze-donut.png",
    "img/sandwich.png",
    "img/sawarma.png",
  ];

  const app = {
    time: null,
    firstTime: false,
    activeMenu: "pos",
    txnType: "sale",
    loadingSampleData: false,
    submitting: false,
    moneys: [2000, 5000, 10000, 20000, 50000, 100000],
    products: [],
    warehouses: Array.isArray(boot.warehouses) ? boot.warehouses : [],
    warehouseId: "",
    warehouseToId: "",
    note: "",
    transactionDate: boot.today || new Date().toISOString().slice(0, 10),
    keyword: "",
    cart: [],
    cash: 0,
    change: 0,
    isShowModalReceipt: false,
    receiptNo: null,
    receiptDate: null,
    einvoiceStatus: "",
    einvoiceMessage: "",
    einvoiceNumber: "",
    einvoiceLookupCode: "",
    einvoiceUrl: "",
    async initDatabase() {
      this.loadProducts();
      if (this.warehouses.length > 0) {
        this.warehouseId = this.warehouses[0].id;
        if (this.warehouses.length > 1) {
          this.warehouseToId = this.warehouses[1].id;
        }
      }
      this.updateChange();
    },
    setTxnType(type) {
      this.txnType = type;
      if (type !== "sale") {
        this.cash = 0;
        this.change = 0;
      } else {
        this.updateChange();
      }
      if (type !== "transfer") {
        this.warehouseToId = "";
      } else if (!this.warehouseToId) {
        const targetWarehouse = this.warehouses.find((warehouse) => warehouse.id !== this.warehouseId);
        this.warehouseToId = targetWarehouse ? targetWarehouse.id : "";
      }
    },
    currentTxnLabel() {
      const labels = {
        sale: "Sale",
        return: "Return",
        stock_in: "Stock In",
        stock_out: "Stock Out",
        transfer: "Transfer",
      };
      return labels[this.txnType] || "Sale";
    },
    currentSubmitLabel() {
      const labels = {
        sale: "SUBMIT SALE",
        return: "SUBMIT RETURN",
        stock_in: "SUBMIT STOCK IN",
        stock_out: "SUBMIT STOCK OUT",
        transfer: "SUBMIT TRANSFER",
      };
      return labels[this.txnType] || "SUBMIT";
    },
    currentEndpoint() {
      const endpoints = {
        sale: boot.saleEndpoint || "/pos/sales",
        return: boot.returnEndpoint || "/pos/returns",
        stock_in: boot.stockInEndpoint || "/pos/stock-in",
        stock_out: boot.stockOutEndpoint || "/pos/stock-out",
        transfer: boot.stockTransferEndpoint || "/pos/stock-transfer",
      };
      return endpoints[this.txnType] || endpoints.sale;
    },
    loadProducts() {
      const source = Array.isArray(boot.products) ? boot.products : [];
      this.products = source.map((product, index) => ({
        id: product.id,
        name: product.name,
        price: Number(product.price || 0),
        option: null,
        image: fallbackImages[index % fallbackImages.length],
      }));
    },
    startWithSampleData() {
      this.firstTime = false;
    },
    startBlank() {
      this.firstTime = false;
    },
    filteredProducts() {
      const keyword = (this.keyword || "").trim().toLowerCase();
      return this.products.filter((p) => !keyword || (p.name || "").toLowerCase().includes(keyword));
    },
    addToCart(product) {
      const index = this.findCartIndex(product);
      if (index === -1) {
        this.cart.push({
          productId: product.id,
          image: product.image,
          name: product.name,
          price: product.price,
          option: product.option,
          qty: 1,
        });
      } else {
        this.cart[index].qty += 1;
      }
      this.beep();
      this.updateChange();
    },
    findCartIndex(product) {
      return this.cart.findIndex((p) => p.productId === product.id);
    },
    addQty(item, qty) {
      const index = this.cart.findIndex((i) => i.productId === item.productId);
      if (index === -1) {
        return;
      }
      const afterAdd = item.qty + qty;
      if (afterAdd === 0) {
        this.cart.splice(index, 1);
        this.clearSound();
      } else {
        this.cart[index].qty = afterAdd;
        this.beep();
      }
      this.updateChange();
    },
    addCash(amount) {
      this.cash = (this.cash || 0) + amount;
      this.updateChange();
      this.beep();
    },
    getItemsCount() {
      return this.cart.reduce((count, item) => count + item.qty, 0);
    },
    updateChange() {
      this.change = this.txnType === "sale" ? this.cash - this.getTotalPrice() : 0;
    },
    updateCash(value) {
      this.cash = parseFloat((value || "").replace(/[^0-9]+/g, "")) || 0;
      this.updateChange();
    },
    getTotalPrice() {
      return this.cart.reduce((total, item) => total + item.qty * item.price, 0);
    },
    sourceWarehouseName() {
      const warehouse = this.warehouses.find((item) => item.id === this.warehouseId) || this.warehouses[0];
      return warehouse ? `${warehouse.name} (${warehouse.code})` : "N/A";
    },
    availableTransferWarehouses() {
      return this.warehouses.filter((warehouse) => warehouse.id !== this.warehouseId);
    },
    submitable() {
      if (!this.warehouseId || this.cart.length === 0 || this.submitting) {
        return false;
      }
      if (this.txnType === "sale") {
        return this.change >= 0;
      }
      if (this.txnType === "transfer") {
        return !!this.warehouseToId && this.warehouseToId !== this.warehouseId;
      }
      return true;
    },
    async submit() {
      if (!this.submitable()) {
        return;
      }

      const endpoint = this.currentEndpoint();
      const csrfToken =
        boot.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";
      const payload = new URLSearchParams();
      const items = this.cart.map((item) => ({
        product_id: item.productId,
        quantity: item.qty,
        unit_price: item.price,
      }));

      payload.append("_token", csrfToken);
      payload.append("warehouse_id", this.warehouseId);
      if (this.txnType === "transfer") {
        payload.append("warehouse_to_id", this.warehouseToId);
      }
      payload.append("transaction_date", this.transactionDate || boot.today || new Date().toISOString().slice(0, 10));
      payload.append("note", this.note || "");
      payload.append("items", JSON.stringify(items));

      this.submitting = true;
      try {
        const response = await fetch(endpoint, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrfToken,
          },
          body: payload.toString(),
        });

        const result = await response.json().catch(() => ({}));
        if (!response.ok || result.status === false) {
          throw new Error(result.message || `Cannot save ${this.currentTxnLabel().toLowerCase()}.`);
        }

        this.isShowModalReceipt = true;
        this.receiptNo =
          (result.data && result.data.code) ||
          `${this.currentTxnLabel().toUpperCase().replace(/\s+/g, "-")}-${Math.round(new Date().getTime() / 1000)}`;
        this.receiptDate = this.dateFormat(new Date());
        this.einvoiceStatus = result.einvoice?.status || "";
        this.einvoiceMessage = result.einvoice?.message || "";
        this.einvoiceNumber = result.einvoice?.invoice_no || "";
        this.einvoiceLookupCode = result.einvoice?.lookup_code || "";
        this.einvoiceUrl = result.einvoice?.invoice_url || "";
      } catch (error) {
        alert(error.message || `Cannot save ${this.currentTxnLabel().toLowerCase()}.`);
      } finally {
        this.submitting = false;
      }
    },
    closeModalReceipt() {
      this.isShowModalReceipt = false;
    },
    dateFormat(date) {
      const formatter = new Intl.DateTimeFormat("vi", {
        dateStyle: "short",
        timeStyle: "short",
      });
      return formatter.format(date);
    },
    numberFormat(number) {
      return (number || "")
        .toString()
        .replace(/^0|\./g, "")
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    },
    priceFormat(number) {
      return number ? `Rp. ${this.numberFormat(number)}` : "Rp. 0";
    },
    clear() {
      this.cash = 0;
      this.note = "";
      this.cart = [];
      this.receiptNo = null;
      this.receiptDate = null;
      this.einvoiceStatus = "";
      this.einvoiceMessage = "";
      this.einvoiceNumber = "";
      this.einvoiceLookupCode = "";
      this.einvoiceUrl = "";
      this.updateChange();
      this.clearSound();
    },
    beep() {
      this.playSound("sound/beep-29.mp3");
    },
    clearSound() {
      this.playSound("sound/button-21.mp3");
    },
    playSound(src) {
      const sound = new Audio();
      sound.src = src;
      sound.play();
      sound.onended = () => delete sound;
    },
    printAndProceed() {
      const receiptContent = document.getElementById("receipt-content");
      const titleBefore = document.title;
      const printArea = document.getElementById("print-area");

      printArea.innerHTML = receiptContent.innerHTML;
      document.title = this.receiptNo;
      window.print();
      this.isShowModalReceipt = false;
      printArea.innerHTML = "";
      document.title = titleBefore;
      this.clear();
    },
  };

  return app;
}
