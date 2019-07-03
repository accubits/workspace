import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { InformDetailComponent } from './inform-detail.component';

describe('InformDetailComponent', () => {
  let component: InformDetailComponent;
  let fixture: ComponentFixture<InformDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InformDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InformDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
