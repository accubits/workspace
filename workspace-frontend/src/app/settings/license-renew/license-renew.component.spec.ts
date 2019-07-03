import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenseRenewComponent } from './license-renew.component';

describe('LicenseRenewComponent', () => {
  let component: LicenseRenewComponent;
  let fixture: ComponentFixture<LicenseRenewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenseRenewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenseRenewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
