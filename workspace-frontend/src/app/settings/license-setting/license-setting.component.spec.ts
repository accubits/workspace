import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenseSettingComponent } from './license-setting.component';

describe('LicenseSettingComponent', () => {
  let component: LicenseSettingComponent;
  let fixture: ComponentFixture<LicenseSettingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenseSettingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenseSettingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
