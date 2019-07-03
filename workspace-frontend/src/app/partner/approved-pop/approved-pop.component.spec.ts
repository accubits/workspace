import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ApprovedPopComponent } from './approved-pop.component';

describe('ApprovedPopComponent', () => {
  let component: ApprovedPopComponent;
  let fixture: ComponentFixture<ApprovedPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ApprovedPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ApprovedPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
