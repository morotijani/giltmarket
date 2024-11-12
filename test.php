1. by exporting make sure you include year to the month export
2. add reverse delete requests


<div class="card bg-success-subtle border-transparent">
              		<div class="card-body">
                		<div class="row align-items-center">
							<div class="col">
								<!-- Heading -->
								<h4 class="fs-base fw-normal text-body-secondary mb-1">
									<?php 
										if (admin_has_permission()) {
											echo 'Today';
										} else if (admin_has_permission('supervisor')) {
											echo 'Gold given';
										} else {
											echo 'Money given';
										}
									?>
								</h4>

								<!-- Text -->
								<div class="fs-5 fw-semibold">
									<?= ((admin_has_permission()) ? money(total_amount_today($admin_id)) : money(_capital($admin_id)['today_capital'])); ?>
									<?php if (admin_has_permission('supervisor')): ?>
										<sub class="fw-normal fs-sm"><?= money(remaining_gold_balance($admin_id)); ?></sub>
									<?php endif; ?>
								</div>
							</div>
							<div class="col-auto">
								<!-- Avatar -->
								<div class="avatar avatar-lg bg-body text-warning" data-bs-target="<?= ((admin_has_permission()) ? '' : '#buyModal'); ?>" data-bs-toggle="modal" style="cursor: pointer;">
									<i class="fs-4" data-duoicon="credit-card"></i>
								</div>
							</div>
							</div>
						</div>
            		</div>