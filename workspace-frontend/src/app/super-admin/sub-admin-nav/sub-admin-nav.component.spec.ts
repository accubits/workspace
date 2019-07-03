import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SubAdminNavComponent } from './sub-admin-nav.component';

describe('SubAdminNavComponent', () => {
  let component: SubAdminNavComponent;
  let fixture: ComponentFixture<SubAdminNavComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SubAdminNavComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SubAdminNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
