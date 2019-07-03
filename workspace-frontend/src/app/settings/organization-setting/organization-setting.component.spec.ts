import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OrganizationSettingComponent } from './organization-setting.component';

describe('OrganizationSettingComponent', () => {
  let component: OrganizationSettingComponent;
  let fixture: ComponentFixture<OrganizationSettingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OrganizationSettingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OrganizationSettingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
