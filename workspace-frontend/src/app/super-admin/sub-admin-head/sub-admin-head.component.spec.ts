import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SubAdminHeadComponent } from './sub-admin-head.component';

describe('SubAdminHeadComponent', () => {
  let component: SubAdminHeadComponent;
  let fixture: ComponentFixture<SubAdminHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SubAdminHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SubAdminHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
