<script setup>
import moneyFormater from '@/utils/moneyFormater'

const props = defineProps({
  auction: {
    type: Object,
    required: true,
    default: () => ({
      id: null,
      harga_limit: 0,
      periode: { mulai: '', selesai: '' },
      barang: {
        id: null,
        nama: '',
        thumbnail_url: '',
        deskripsi: '',
        harga_awal: 0,
      },
    }),
  },
})
</script>

<template>
  <div class="product-card">
    <div class="product-shot">
      <img
        class="product-thumbnail"
        :src="auction.barang?.thumbnail_url || '/placeholder-image.webp'"
        :alt="auction.barang?.nama"
        loading="lazy"
      />
    </div>

    <div class="product-body">
      <h3 class="product-title">{{ auction.barang?.nama }}</h3>

      <div class="product-group">
        <div class="info-item">
          <span class="label">Harga Awal</span>
          <p class="value">{{ moneyFormater(auction.barang?.harga_awal) }}</p>
        </div>
        <span class="divider"></span>
        <div class="info-item">
          <span class="label">Harga Limit</span>
          <p class="value">{{ moneyFormater(auction.harga_limit) }}</p>
        </div>
      </div>

      <!-- <div class="badge" :class="statusBadgeClass">
        <span>{{ auction.status?.toUpperCase() }}</span>
      </div> -->

      <p class="product-description">
        {{ auction.barang?.deskripsi }}
      </p>
    </div>

    <!-- Container Slot untuk tombol aksi (misal: Button 'Ikut Lelang') -->
    <div v-if="$slots.default" class="product-actions">
      <slot></slot>
    </div>
  </div>
</template>

<style scoped>
.product-card {
  display: flex;
  flex-direction: column;
  border-radius: 12px;
  max-width: 320px;
  width: 100%;
  border: 1px solid #e5e7eb;
  background-color: #ffffff;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease;
}

.product-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.product-shot {
  width: 100%;
  height: 180px;
  position: relative;
  background-color: #f3f4f6;
}

.product-thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.timer-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  background: linear-gradient(180deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0) 100%);
  color: #ffffff;
  font-weight: 600;
  font-size: 12px;
  text-align: center;
  padding: 8px 12px;
}

.product-body {
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.product-title {
  font-size: 16px;
  font-weight: 600;
  color: #111827;
  margin: 0;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.product-group {
  margin-top: 4px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #f9fafb;
  padding: 8px 12px;
  border-radius: 8px;
}

.info-item .label {
  font-size: 11px;
  color: #6b7280;
  display: block;
}

.info-item .value {
  font-weight: 600;
  font-size: 13px;
  color: #1f2937;
  margin: 2px 0 0 0;
}

.divider {
  width: 1px;
  height: 24px;
  background-color: #e5e7eb;
}

.badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.5px;
  width: max-content;
}

.badge-open {
  background-color: #ecfdf5;
  color: #059669;
  border: 1px solid #a7f3d0;
}

.badge-closed {
  background-color: #fef2f2;
  color: #dc2626;
  border: 1px solid #fecaca;
}

.badge-pending {
  background-color: #fffbeb;
  color: #d97706;
  border: 1px solid #fde68a;
}

.product-description {
  font-size: 13px;
  color: #4b5563;
  margin: 0;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.product-actions {
  padding: 0 16px 16px 16px;
}
</style>
