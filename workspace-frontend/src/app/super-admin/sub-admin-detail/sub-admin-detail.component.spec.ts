import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SubAdminDetailComponent } from './sub-admin-detail.component';

describe('SubAdminDetailComponent', () => {
  let component: SubAdminDetailComponent;
  let fixture: ComponentFixture<SubAdminDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SubAdminDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SubAdminDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
