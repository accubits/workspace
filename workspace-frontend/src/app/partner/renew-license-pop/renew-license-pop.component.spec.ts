import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RenewLicensePopComponent } from './renew-license-pop.component';

describe('RenewLicensePopComponent', () => {
  let component: RenewLicensePopComponent;
  let fixture: ComponentFixture<RenewLicensePopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RenewLicensePopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RenewLicensePopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
